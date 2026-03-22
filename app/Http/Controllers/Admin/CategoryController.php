<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\CategoryService;
use App\Services\TagService;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function __construct(
        private CategoryService $categoryService,
        private TagService $tagService,
    ) {}

    public function index()
    {
        $categories = $this->categoryService->paginate();

        return view('admin.categories.index', [
            'categories' => $categories,
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => ['required', 'unique:categories,title'],
        ]);

        $this->categoryService->create($data);

        return to_route('admin.categories.index');
    }

    public function edit(string $id)
    {
        $category = $this->categoryService->find($id);

        abort_if(is_null($category), 404);

        $tags = $this->tagService->getOptions();
        $selectedTags = $category->tags->pluck('id')->toArray();
        
        return view('admin.categories.edit', [
            'category' => $category,
            'tags' => $tags,
            'selectedTags' => $selectedTags,
        ]);
    }

    public function update(Request $request, string $id)
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'tags' => ['array'],
            'tags.*' => ['exists:tags,id'],
        ]);

        $category = $this->categoryService->update($data, $id);
        
        if (is_null($category)) {
            return back()->withErrors(['category' => 'Failed to update category.']);
        }

        return redirect()->route('admin.categories.index');
    }

    public function destroy(string $id)
    {
        $this->categoryService->delete($id);

        return response()->json([
            'success' => true,
            'message' => 'Category deleted successfully.',
        ]);
    }
}
