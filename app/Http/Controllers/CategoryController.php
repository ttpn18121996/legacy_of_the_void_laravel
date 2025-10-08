<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Video;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function show(Request $request, string $slug)
    {
        $q = $request->query('q');
        $category = Category::where('slug', $slug)->firstOrFail();

        $videos = Video::with(['thumbnails', 'tags'])
            ->whereHas('categories', function ($query) use ($slug) {
                $query->where('slug', $slug);
            })
            ->when($q, function ($query) use ($q) {
                $query->where('title', 'like', "%{$q}%");
            })
            ->latest()
            ->paginate(20)
            ->onEachSide(2)
            ->withQueryString();

        return view('videos.index', [
            'videos' => $videos,
            'category' => $category,
        ]);
    }
}
