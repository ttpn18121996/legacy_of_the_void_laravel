<?php

namespace App\Services;

use App\Models\Actress;
use App\Models\Tag;
use App\Models\Video;
use App\Models\VideoThumbnail;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Log;

class VideoService
{
    public function __construct(
        private Video $video,
    ) {}

    public function paginateWithThumbnails(array $filters = [])
    {
        $q = Arr::get($filters, 'q');
        $tagSlugs = Arr::get($filters, 'tags', []);
        $videoIds = [];

        if ($q) {
            $videoIds = Tag::where('title', 'like', "%{$q}%")
                ->join('tag_video', 'tags.id', '=', 'tag_video.tag_id')
                ->pluck('tag_video.video_id');
        }

        $videos = Video::with(['thumbnails', 'tags'])
            ->when($q, function ($query) use ($q, $videoIds) {
                $query->where('title', 'like', "%{$q}%")
                    ->orWhereIn('id', $videoIds);
            })
            ->when(count($tagSlugs), function ($query) use ($tagSlugs) {
                $query->whereHas('tags', function ($query) use ($tagSlugs) {
                    $query->whereIn('slug', $tagSlugs);
                }, '=', count($tagSlugs));
            })
            ->latest()
            ->paginate(20)
            ->onEachSide(2)
            ->withQueryString();

        return $videos;
    }

    public function find(string $id): Video
    {
        return Video::with(['thumbnails', 'tags', 'actresses', 'categories'])
            ->where('id', $id)
            ->firstOrFail();
    }

    public function update(array $data, string $id): Video
    {
        $video = Video::findOrFail($id);

        $video->update([
            'title' => $data['title'],
        ]);

        if (isset($data['actresses'])) {
            $video->actresses()->sync($data['actresses']);
        }

        if (isset($data['tags'])) {
            $video->tags()->sync($data['tags']);
        }

        VideoThumbnail::where('video_id', $video->id)->get()->each(function ($thumbnail, $index) use ($data) {
            if (isset($data['default_thumbnail']) && $data['default_thumbnail'] == $index + 1) {
                $thumbnail->update(['is_default' => true]);
            } else {
                $thumbnail->update(['is_default' => false]);
            }
        });

        return $video;
    }

    public function delete(string $id)
    {
        $video = Video::findOrFail($id);

        $result = $this->moveToTrash($video->title, 'videos');

        if (! $result) {
            Log::error("Failed to move video to trash: {$video->title}");

            return false;
        }

        $video->thumbnails()->delete();
        $video->tags()->detach();
        $video->actresses()->detach();
        $video->categories()->detach();
        $video->delete();

        return true;
    }

    public function moveToTrash($title, $from = 'reviews')
    {
        $videoPath = storage_path("app/public/{$from}/{$title}.mp4");
        $pointPath = storage_path("app/public/trash/{$title}.mp4");

        if (! file_exists($videoPath)) {
            FacadesLog::error("Video not found: {$videoPath}");

            return false;
        }

        if (! rename($videoPath, $pointPath)) {
            Log::error("Failed to move video: {$videoPath}");

            return false;
        }

        return true;
    }

    public function syncActresses(string $videoId, array $actresses): void
    {
        $video = Video::find($videoId);
        $video->actresses()->sync($actresses);

        $tags = Actress::whereIn('id', $actresses)->with(['tags'])
            ->get()
            ->flatMap(function ($actress) {
                return $actress->tags->pluck('id');
            })
            ->merge($video->tags->pluck('id'))
            ->unique()
            ->toArray();

        $video->tags()->sync($tags);
    }

    public function search(string $keyword)
    {
        return $this->video->where('title', 'like', "%{$keyword}%")
            ->orderByDesc('latest_like')
            ->orderBy('title')
            ->limit(10)
            ->get(['id', 'title']);
    }

    public function incrementLike(string $videoId): bool
    {
        $video = Video::find($videoId);
        if ($video->latest_like && $video->latest_like > now()->subDay()) {
            return false;
        }

        try {
            $video->latest_like = now();
            $video->like = $video->like + 1;
            $video->saveQuietly();

            return true;
        } catch (\Exception $e) {
            Log::error("Error incrementing like for video ID {$videoId}: " . $e->getMessage());
            return false;
        }
    }
}
