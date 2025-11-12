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
        return view('yolo-explorer', ['videos' => $videos]);
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
            $flaskResponse = Http::timeout(30) // 30 second timeout
                ->attach('video', file_get_contents(Storage::path($path)), $filename) // <-- Diperbaiki
                ->post(config('services.flask.url') . '/process_video', [
                    'video_id' => $video->id,
                    'webhook_url' => url('/api/videos/webhook'),
                ]);

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
        $validated = $request->validate([
            'video_id' => 'required|integer|exists:videos,id',
            'status' => 'required|string',
            'processed_filename' => 'nullable|string',
            'details' => 'nullable|array',
        ]);

        $video = Video::find($validated['video_id']);

        if (!$video) {
            Log::error('Webhook received for non-existent video_id: ' . $validated['video_id']);
            return response()->json(['message' => 'Video not found.'], 404);
        }

        // Ignore webhook if the job was already cancelled by the user
        if ($video->status === 'cancelled') {
            Log::info('Webhook ignored for cancelled video_id: ' . $validated['video_id']);
            return response()->json(['message' => 'Webhook ignored for cancelled job.']);
        }

        $video->status = $validated['status'];
        $video->processed_filename = $validated['processed_filename'];
        $video->details = $validated['details'] ?? null;
        $video->save();

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

        // Delete processed file (if it exists)
        // Note: This file is on the Flask server, so we can't delete it directly.
        // This action will just remove the record from the Laravel DB.
        // For a full cleanup, an API call to the Flask server would be needed.

        $video->delete();

        return back()->with('success', 'Video telah berhasil dihapus.')->with('activeTab', 'history');
    }
}
