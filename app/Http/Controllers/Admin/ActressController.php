<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Tag;
use App\Services\ActressService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;

class ActressController extends Controller
{
    public function __construct(
        protected ActressService $actressService,
    ) {}

    public function index(Request $request)
    {
        $q = $request->query('q');

        $actresses = $this->actressService->paginate(['q' => $q]);

        return view('admin.actresses.index', [
            'actresses' => $actresses,
        ]);
    }

    public function create()
    {
        $tags = Tag::orderBy('title')->get()
            ->map(fn ($tag) => ['value' => $tag->id, 'label' => "#{$tag->title}"])
            ->toArray();

        return view('admin.actresses.create', [
            'tags' => $tags,
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'unique:actresses,name'],
            'another_name' => ['nullable'],
            'tags' => ['array'],
        ]);

        $result = $this->actressService->create([
            'name' => $data['name'],
            'another_name' => $data['another_name'] ?? null,
            'tags' => $data['tags'] ?? [],
        ]);

        if (! $result) {
            return back()->withErrors(['actress' => 'Failed to create actress.']);
        }

        return to_route('admin.actresses.index');
    }

    public function edit(string $id)
    {
        $actress = $this->actressService->find($id);

        if (! $actress) {
            return redirect()->route('admin.actresses.index')->withErrors(['actress' => 'Actress not found.']);
        }

        $tags = Tag::orderBy('title')->get()
            ->map(fn ($tag) => ['value' => $tag->id, 'label' => "#{$tag->title}"])
            ->toArray();
        $selectedTags = $actress->tags->pluck('id')->toArray();

        return view('admin.actresses.edit', [
            'actress' => $actress,
            'tags' => $tags,
            'selectedTags' => $selectedTags,
        ]);
    }

    public function update(Request $request, string $id)
    {
        $data = $request->validate([
            'name' => ['required', 'unique:actresses,name,'.$id.',id'],
            'another_name' => ['nullable'],
            'tags' => ['array'],
        ]);

        $result = $this->actressService->update([
            'name' => $data['name'],
            'another_name' => $data['another_name'] ?? null,
            'tags' => $data['tags'] ?? [],
        ], $id);

        if (! $result) {
            return back()->withErrors(['actress' => 'Failed to create actress.']);
        }

        return to_route('admin.actresses.index');
    }

    public function updateThumbnail(string $id)
    {
        $actress = $this->actressService->find($id);
        Artisan::call('app:sync-actress-thumbnail', [
            '--name' => $actress->name,
        ]);

        return response()->json(['success' => true]);
    }

    public function destroy(string $id)
    {
        $this->actressService->delete($id);

        return response()->json([
            'success' => true,
            'message' => 'Delete successfully.',
        ]);
    }
}
