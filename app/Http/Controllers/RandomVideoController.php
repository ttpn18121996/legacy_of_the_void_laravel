<?php

namespace App\Http\Controllers;

use App\Services\VideoService;
use Illuminate\Http\Request;

class RandomVideoController extends Controller
{
    public function __construct(
        protected VideoService $videoService,
    ) {}

    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        $videos = $this->videoService->getRandomVideos($request->query('limit', 5));

        return view('videos.index', [
            'videos' => $videos,
        ]);
    }
}
