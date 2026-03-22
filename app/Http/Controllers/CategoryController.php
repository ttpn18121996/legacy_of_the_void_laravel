<?php

namespace App\Http\Controllers;

use App\Services\CategoryService;
use App\Services\VideoService;

class CategoryController extends Controller
{
    public function __construct(
        protected CategoryService $categoryService,
        protected VideoService $videoService,
    ) {}
    
    public function index()
    {
        return view('categories.index', [
            'categories' => $this->categoryService->paginate(),
        ]);
    }

    public function show(string $slug)
    {
        $category = $this->categoryService->findBySlug($slug);
        $tags = $category->tags;
        $videos = $this->videoService->paginateWithThumbnailsByTags($tags->pluck('id')->toArray());
        
        return view('videos.index', [
            'videos' => $videos,
            'filteredTags' => $tags,
        ]);
    }
}
