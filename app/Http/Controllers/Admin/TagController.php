<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\TagService;
use Illuminate\Http\Request;

class TagController extends Controller
{
    public function __construct(
        protected TagService $tagService,
    ) {}

    public function index(Request $request)
    {
        return view('admin.tags.index', [
            'tags' => $this->tagService->all($request->query()),
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required',
        ]);

        $this->tagService->create($data);

        return to_route('admin.tags.index');
    }

    public function update(Request $request, string $id)
    {
        $data = $request->validate([
            'title' => ['required', 'unique:tags,title,'.$id],
        ]);

        $title = str($data['title'])->lower()->toString();
        $slug = str($title)->slug()->toString();

        $tag = $this->tagService->update([
            'title' => $title,
            'slug' => $slug,
        ], $id);

        return response()->json([
            'success' => true,
            'message' => 'Tag updated successfully.',
            'data' => $tag,
        ]);
    }

    public function destroy(string $id)
    {
        $this->tagService->delete($id);

        return response()->json([
            'success' => true,
            'message' => 'Tag deleted successfully.',
        ]);
    }
}
