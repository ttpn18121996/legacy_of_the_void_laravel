<?php

namespace App\Http\Controllers;

use App\Enums\SearchType;
use App\Models\Tag;
use App\Models\Video;
use App\Services\ActressService;
use App\Services\VideoService;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function __construct(
        protected ActressService $actressService,
        protected VideoService $videoService,
    ) {}

    public function index()
    {
        $tags = Tag::orderBy('title')->get();
        $totalVideos = Video::selectRaw('count(id) as total')->first()?->total;

        return view('home.index', [
            'tags' => $tags,
            'totalVideos' => $totalVideos,
            'totalUnapprovedVideos' => $this->getTotalUnapprovedVideos(),
            'totalUnpublishedVideos' => $this->getTotalUnpublishedVideos(),
        ]);
    }

    private function getTotalUnapprovedVideos(): int
    {
        $reviewPath = storage_path('app/public/reviews');
        $videos = scandir($reviewPath);
        $total = 0;

        if ($videos !== false) {
            $total = collect($videos)
                ->filter(fn ($file) => $file !== '.' && $file !== '..')
                ->count();
        }

        return $total;
    }

    private function getTotalUnpublishedVideos(): int
    {
        $reviewPath = storage_path('app/public/approved');
        $videos = scandir($reviewPath);
        $total = 0;

        if ($videos !== false) {
            $total = collect($videos)
                ->filter(fn ($file) => $file !== '.' && $file !== '..')
                ->count();
        }

        return $total;
    }

    public function search(Request $request)
    {
        $keyword = $request->input('keyword');
        $searchType = $request->input('search_type') === SearchType::ACTRESS->value ? SearchType::ACTRESS->value : SearchType::VIDEO->value;
        $selectedTags = $request->input('tags', []);

        if (empty($keyword) && empty($selectedTags)) {
            return redirect()->route('home');
        }

        if ($searchType === SearchType::VIDEO->value) {
            $results = $this->videoService->paginateWithThumbnails([
                'q' => $keyword,
                'tags' => $selectedTags,
            ]);
        } else {
            $results = $this->actressService->paginate([
                'q' => $keyword,
                'tags' => $selectedTags,
            ]);
        }

        $tags = Tag::orderBy('title')->get();

        return view('home.search-results', [
            'tags' => $tags,
            'keyword' => $keyword,
            'searchType' => $searchType,
            'selectedTags' => $selectedTags,
            'results' => $results,
        ]);
    }
}
