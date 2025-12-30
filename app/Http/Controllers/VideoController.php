<?php

namespace App\Http\Controllers;

use App\Models\Video;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage; // <-- Ditambahkan

class VideoController extends Controller
{
    /**
     * Display the main view with video history.
     */
    public function index()
    {
        $videos = Video::where('user_id', Auth::id())->latest()->get();
        return view('mediapipe-explorer', ['videos' => $videos]);
    }

    /**
     * Handle the video upload and dispatch to Flask service.
     */
    public function upload(Request $request)
    {
        $request->validate([
            'video' => 'required|file|mimetypes:video/mp4,video/quicktime,video/x-msvideo|max:102400', // Max 100MB
        ]);

        $file = $request->file('video');
        $filename = time() . '_' . $file->getClientOriginalName();

        // 1. Create initial record in the database
        $video = Video::create([
            'filename' => $filename,
            'status' => 'pending',
            'user_id' => Auth::id(),
        ]);

        // 2. Store the video file locally first
        $path = $file->storeAs('public/videos/originals', $filename);

        if (!$path) {
            $video->update(['status' => 'failed']);
            return back()->with('error', 'Gagal menyimpan file video.')->with('activeTab', 'history');
        }

        // 3. Dispatch the video to the Flask service
        try {
            // Use a stream to avoid loading the whole file into memory
            $videoStream = fopen(Storage::path($path), 'r');

            $flaskResponse = Http::timeout(120) // 120 second timeout
                ->attach('video', $videoStream, $filename)
                ->post(config('services.flask.url') . '/process_video', [
                    'video_id' => $video->id,
                    'webhook_url' => route('videos.webhook'),
                ]);
            
            if (is_resource($videoStream)) {
                fclose($videoStream);
            }

            // 4. Handle the response from Flask
            if ($flaskResponse->successful()) {
                $video->update(['status' => 'processing']);
                return back()->with('success', 'Video telah diterima dan sedang diproses.')->with('activeTab', 'history');
            } else {
                $video->update(['status' => 'failed']);
                Log::error('Flask API Error: ' . $flaskResponse->body());
                return back()->with('error', 'Gagal mengirim video ke server pemrosesan.')->with('activeTab', 'history');
            }
        } catch (\Exception $e) {
            $video->update(['status' => 'failed']);
            Log::error('Failed to connect to Flask service: ' . $e->getMessage());
            return back()->with('error', 'Tidak dapat terhubung ke server pemrosesan. Pastikan server Flask berjalan.')->with('activeTab', 'history');
        }
    }

    /**
     * Handles incoming webhook from the Flask service.
     */
    public function handleWebhook(Request $request)
    {
        Log::info('--- [WEBHOOK_DEBUG] --- Webhook handle method STARTED.');
        Log::info('--- [WEBHOOK_DEBUG] --- Raw Request Body: ' . $request->getContent());
        Log::info('--- [WEBHOOK_DEBUG] --- Request Data Array: ', $request->all());

        try {
            Log::info('--- [WEBHOOK_DEBUG] --- Before validation.');
            $validated = $request->validate([
                'video_id' => 'required|integer|exists:videos,id',
                'status' => 'required|string',
                'processed_filename' => 'nullable|string',
                'details' => 'nullable|array',
            ]);
            Log::info('--- [WEBHOOK_DEBUG] --- Validation SUCCEEDED for video_id: ' . $validated['video_id']);
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('--- [WEBHOOK_DEBUG] --- Validation FAILED.');
            Log::error($e->getMessage());
            Log::error(json_encode($e->errors()));
            return response()->json(['message' => 'Validation failed.', 'errors' => $e->errors()], 422);
        }

        Log::info('--- [WEBHOOK_DEBUG] --- Finding video with ID: ' . $validated['video_id']);
        $video = Video::find($validated['video_id']);

        if (!$video) {
            Log::error('--- [WEBHOOK_DEBUG] --- Webhook FAILED: Video not found with id: ' . $validated['video_id']);
            return response()->json(['message' => 'Video not found.'], 404);
        }
        Log::info('--- [WEBHOOK_DEBUG] --- Video FOUND. Current status: ' . $video->status);

        // Ignore webhook if the job was already cancelled by the user
        if ($video->status === 'cancelled') {
            Log::info('--- [WEBHOOK_DEBUG] --- Webhook ignored for cancelled video_id: ' . $validated['video_id']);
            return response()->json(['message' => 'Webhook ignored for cancelled job.']);
        }

        Log::info('--- [WEBHOOK_DEBUG] --- Updating video attributes...');
        $video->status = $validated['status'];
        $video->processed_filename = $validated['processed_filename'] ?? null;
        $video->details = $validated['details'] ?? null;
        
        try {
            Log::info('--- [WEBHOOK_DEBUG] --- Attempting to SAVE video record to database...');
            $video->save();
            Log::info('--- [WEBHOOK_DEBUG] --- SUCCESS: Video record SAVED to database!');
        } catch (\Exception $e) {
            Log::error('--- [WEBHOOK_DEBUG] --- DATABASE ERROR: Failed to save video record. Error: ' . $e->getMessage());
            // Still return a 200 OK so Celery doesn't retry, but log the critical error.
            return response()->json(['message' => 'Database save failed.'], 500);
        }

        Log::info('--- [WEBHOOK_DEBUG] --- Webhook Processed Successfully ---');
        return response()->json(['message' => 'Webhook processed successfully.']);
    }

    /**
     * Cancel a video processing job.
     */
    public function cancel(Video $video)
    {
        // Authorize
        if (Auth::id() !== $video->user_id) {
            abort(403);
        }

        if (in_array($video->status, ['pending', 'processing'])) {
            $video->update(['status' => 'cancelled']);
            return back()->with('success', 'Proses video telah dibatalkan.')->with('activeTab', 'history');
        }

        return back()->with('error', 'Video ini tidak dapat dibatalkan.')->with('activeTab', 'history');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Video $video)
    {
        // Authorize
        if (Auth::id() !== $video->user_id) {
            abort(403);
        }

        // Delete original file
        $originalPath = 'public/videos/originals/' . $video->filename;
        if (Storage::exists($originalPath)) {
            Storage::delete($originalPath);
        }

        // Delete processed file from Flask if it exists.
        if (!empty($video->processed_filename)) {
            try {
                $flaskUrl = rtrim(config('services.flask.url'), '/');
                $deleteUrl = $flaskUrl . '/api/processed/' . rawurlencode($video->processed_filename);
                Http::timeout(10)->delete($deleteUrl);
            } catch (\Exception $e) {
                Log::error('Failed to delete processed file on Flask: ' . $e->getMessage());
            }
        }

        $video->delete();

        return back()->with('success', 'Video telah berhasil dihapus.')->with('activeTab', 'history');
    }
}
