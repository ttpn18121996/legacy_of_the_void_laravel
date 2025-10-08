<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Tag;
use App\Models\Video;

class HomeController extends Controller
{
    public function index()
    {
        $categories = Category::orderBy('title')->get();
        $tags = Tag::orderBy('title')->get();
        $totalVideos = Video::selectRaw('count(id) as total')->first()?->total;

        return view('home', [
            'categories' => $categories,
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
}
