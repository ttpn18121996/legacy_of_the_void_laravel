<?php

namespace App\Http\Controllers;

use App\Services\ActressService;
use App\Services\TagService;
use App\Services\VideoService;
use Illuminate\Http\Request;

class OptionController extends Controller
{
    public function __construct(
        protected ActressService $actressService,
        protected VideoService $videoService,
        protected TagService $tagService,
    ) {}

    public function getActresses(Request $request)
    {
        $videoId = $request->query('video_id');
        $video = null;

        if ($videoId) {
            $video = $this->videoService->find($videoId);
        }

        $options = $this->actressService->getOptions($video);

        return response()->json([
            'options' => $options,
        ]);
    }

    public function getTags(Request $request)
    {
        $videoId = $request->query('video_id');
        $video = null;
        
        if ($videoId) {
            $video = $this->videoService->find($videoId);
        }

        $options = $this->tagService->getOptions($video);

        return response()->json([
            'options' => $options,
        ]);
    }
}
