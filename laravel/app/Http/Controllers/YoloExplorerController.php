<?php

namespace App\Http\Controllers;

use App\Models\Video;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class YoloExplorerController extends Controller
{
    public function index()
    {
        $videos = Video::where('user_id', Auth::id())->latest()->get();
        return view('yolo-explorer', compact('videos'));
    }
}