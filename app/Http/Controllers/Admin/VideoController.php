<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Actress;
use App\Models\Tag;
use App\Services\ActressService;
use App\Services\TagService;
use App\Services\VideoService;
use Illuminate\Http\Request;

class VideoController extends Controller
{
    public function __construct(
        protected ActressService $actressService,
        protected VideoService $videoService,
        protected TagService $tagService,
    ) {}

    public function index(Request $request)
    {
        $videos = $this->videoService->paginateWithThumbnails($request->query());

        return view('admin.videos.index', [
            'videos' => $videos,
        ]);
    }

    public function edit(string $id)
    {
        $video = $this->videoService->find($id);

        $actresses = $this->actressService->getOptions();
        $selectedActresses = $video->actresses->pluck('id')->toArray();

        $tags = $this->tagService->getOptions();
        $selectedTags = $video->tags->pluck('id')->toArray();

        return view('admin.videos.edit', [
            'video' => $video,
            'tags' => $tags,
            'selectedTags' => $selectedTags,
            'actresses' => $actresses,
            'selectedActresses' => $selectedActresses,
        ]);
    }

    public function update(Request $request, string $id)
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'default_thumbnail' => ['required', 'integer', 'min:1', 'max:8'],
            'actresses' => ['array'],
            'actresses.*' => ['exists:actresses,id'],
            'tags' => ['array'],
            'tags.*' => ['exists:tags,id'],
        ]);

        $video = $this->videoService->update($data, $id);

        return redirect()->route('admin.videos.index')->with('success', "Video '{$video->title}' updated successfully.");
    }

    public function syncTags(string $id)
    {
        $video = $this->videoService->find($id);
        $actresses = $video->actresses;

        $this->videoService->syncTagsByActress($video, $actresses->pluck('id')->toArray());

        return response()->json(['success' => true]);
    }

    public function destroy(string $id)
    {
        $this->videoService->delete($id);

        return response()->json([
            'success' => true,
            'message' => 'Video deleted successfully.',
        ]);
    }
}
