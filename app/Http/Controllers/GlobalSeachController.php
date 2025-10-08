<?php

namespace App\Http\Controllers;

use App\Services\ActressService;
use App\Services\TagService;
use App\Services\VideoService;
use Illuminate\Http\Request;

class GlobalSeachController extends Controller
{
    public function __construct(
        protected ActressService $actressService,
        protected TagService $tagService,
        protected VideoService $videoService,
    ) {}

    public function __invoke(Request $request)
    {
        $keyword = $request->query('q', '');
        $videos = $this->videoService->search($keyword);
        $actresses = $this->actressService->search($keyword);
        $tags = $this->tagService->search($keyword);

        return response()->json([
            'keyword' => $keyword,
            'videos' => $videos,
            'actresses' => $actresses,
            'tags' => $tags,
        ]);
    }
}
