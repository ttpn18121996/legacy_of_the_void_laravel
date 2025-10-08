<?php

namespace App\Http\Controllers;

use App\Services\ActressService;
use App\Services\TagService;
use App\Services\VideoService;
use Illuminate\Http\Request;

class OptionController extends Controller
{
    public function __construct(
        private ActressService $actressService,
        private VideoService $videoService,
        private TagService $tagService,
    ) {}

    public function getActresses(Request $request)
    {
        $options = $this->actressService->getOptions();
        $videoId = $request->query('video_id');

        if ($videoId) {
            $video = $this->videoService->find($videoId);
            $selected = $video->actresses->pluck('id')->toArray();
            foreach ($options as $key => $option) {
                if (in_array($option['value'], $selected)) {
                    $options[$key]['selected'] = true;
                }
            }
        }

        return response()->json([
            'options' => $options,
            'selected' => $selected,
        ]);
    }

    public function getTags(Request $request)
    {
        $options = $this->tagService->getOptions();
        $videoId = $request->query('video_id');

        if ($videoId) {
            $video = $this->videoService->find($videoId);
            $selected = $video->tags->pluck('id')->toArray();
            foreach ($options as $key => $option) {
                if (in_array($option['value'], $selected)) {
                    $options[$key]['selected'] = true;
                }
            }
        }

        return response()->json([
            'options' => $options,
            'selected' => $selected,
        ]);
    }
}
