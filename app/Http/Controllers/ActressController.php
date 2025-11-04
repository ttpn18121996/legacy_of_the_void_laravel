<?php

namespace App\Http\Controllers;

use App\Models\Actress;
use App\Models\Tag;
use App\Models\Video;
use App\Services\ActressService;
use App\Services\VideoService;
use Illuminate\Http\Request;

class ActressController extends Controller
{
    public function __construct(
        private ActressService $actressService,
        private VideoService $videoService,
    ) {}

    public function index(Request $request)
    {
        $tagSlugs = $request->query('tags', []);
        $filteredTags = Tag::whereIn('slug', $tagSlugs)->get();

        $actresses = $this->actressService->paginate($request->query());

        return view('actresses.index', [
            'actresses' => $actresses,
            'filteredTags' => $filteredTags,
        ]);
    }

    public function show(Request $request, string $id)
    {
        $q = $request->query('q');
        $actress = $this->actressService->find((int) $id);

        $videos = Video::with(['thumbnails'])
            ->when($q, function ($query) use ($q) {
                $query->where('title', 'like', "%{$q}%");
            })
            ->whereHas('actresses', function ($query) use ($id) {
                $query->where('id', $id);
            })
            ->latest()
            ->paginate(20)
            ->onEachSide(2)
            ->withQueryString();

        $tags = Tag::orderBy('title')->get()
            ->map(fn ($tag) => ['value' => $tag->id, 'label' => "#{$tag->title}"])
            ->toArray();
        $selectedTags = $actress->tags->pluck('id')->toArray();

        if ($videos->isEmpty() && $q) {
            return redirect()->route('actresses.index', ['q' => $q]);
        }

        return view('actresses.show', [
            'actress' => $actress,
            'videos' => $videos,
            'tags' => $tags,
            'selectedTags' => $selectedTags,
        ]);
    }

    public function updateTags(Request $request)
    {
        $actressId = $request->input('actress_id');
        $tags = $request->input('tags');

        $actress = Actress::find($actressId);
        $actress->tags()->sync($tags);

        return response()->json(['success' => true]);
    }

    public function getOptions(Request $request)
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
}
