<?php

namespace App\Services;

use App\Enums\PathType;
use App\Enums\SortDestination;
use App\Enums\VideoSort;
use App\Models\Actress;
use App\Models\Tag;
use App\Models\Video;
use App\Models\VideoThumbnail;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use RuntimeException;
use Throwable;

class VideoService
{
    public function __construct(
        protected Video $video,
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

        $builder = Video::with(['thumbnails', 'tags'])
            ->when($q, function ($query) use ($q, $videoIds) {
                $query->where('title', 'like', "%{$q}%")
                    ->orWhere('id', $q)
                    ->orWhereIn('id', $videoIds);
            })
            ->when(count($tagSlugs), function ($query) use ($tagSlugs) {
                $query->whereHas('tags', function ($query) use ($tagSlugs) {
                    $query->whereIn('slug', $tagSlugs);
                }, '=', count($tagSlugs));
            });

        if ($sortBy = Arr::get($filters, 'sort_by')) {
            $destination = Arr::get($filters, 'destination', SortDestination::DESCENDING->value);
            if ($sortBy === VideoSort::MOST_LIKED->value) {
                $builder->orderBy('like', $destination)->orderByDesc('latest_like')->latest();
            } else {
                $builder->orderBy('created_at', $destination);
            }
        } else {
            $builder->latest();
        }

        return $builder->paginate(20)
            ->onEachSide(2)
            ->withQueryString();
    }

    public function paginateWithThumbnailsByTags(array $tagIds)
    {
        return Video::with(['thumbnails', 'tags'])
            ->whereHas('tags', function ($query) use ($tagIds) {
                $query->whereIn('tags.id', $tagIds);
            })
            ->latest()
            ->paginate(20)
            ->onEachSide(2)
            ->withQueryString();
    }

    /**
     * Find video by ID
     * 
     * @param string $id
     * @return Video
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException<Video>
     */
    public function find(string $id): Video
    {
        return Video::with(['thumbnails', 'tags', 'actresses'])
            ->where('id', $id)
            ->firstOrFail();
    }

    /**
     * Update video
     * 
     * @param array $data
     * @param string $id
     * @return Video
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException<Video>
     */
    public function update(array $data, string $id): Video
    {
        $video = Video::findOrFail($id);

        $video->update([
            'title' => $data['title'],
            'path' => $data['path'],
        ]);

        $actresses = Arr::get($data, 'actresses', []);
        $video->actresses()->sync($actresses);
        
        $tags = Arr::get($data, 'tags', []);
        $video->tags()->sync($tags);

        VideoThumbnail::where('video_id', $video->id)->get()->each(function ($thumbnail, $index) use ($data) {
            $dataUpdate = [];
            if (isset($data['thumbnail_directory'])) {
                $dataUpdate['path'] = $data['thumbnail_directory']. "/thumbnail_" . ($index + 1) . '.png';
            }

            if (isset($data['default_thumbnail']) && $data['default_thumbnail'] == $index + 1) {
                $dataUpdate['is_default'] = true;
            } else {
                $dataUpdate['is_default'] = false;
            }

            $thumbnail->update($dataUpdate);
        });

        return $video;
    }

    /**
     * Delete video
     * 
     * @param string $id
     * @return bool
     */
    public function delete(string $id): bool
    {
        try {
            $video = Video::findOrFail($id);
            $videoTitle = $video->title;

            DB::beginTransaction();

            $video->thumbnails()->delete();
            $video->tags()->detach();
            $video->actresses()->detach();
            $video->delete();

            DB::commit();

            $this->moveToTrash($videoTitle, "media/{$videoTitle}");

            return true;
        } catch (Throwable $e) {
            DB::rollBack();
            Log::error($e->getMessage());

            return false;
        }
    }

    /**
     * Move video to trash
     * 
     * @param string $title
     * @param string $from
     * @return void
     * @throws RuntimeException
     */
    public function moveToTrash($title, $from = 'reviews'): void
    {
        $videoPath = storage_path("app/public/{$from}/{$title}.mp4");
        $pointPath = storage_path("app/public/trash/{$title}.mp4");
        $thumbnailPath = storage_path("app/public/{$from}");

        if (! file_exists($videoPath)) {
            throw new RuntimeException("Video not found: {$videoPath}");
        }

        if (! rename($videoPath, $pointPath)) {
            throw new RuntimeException("Failed to move video: {$videoPath}");
        }

        if (file_exists($thumbnailPath)) {
            if (! force_rmdir($thumbnailPath)) {
                throw new RuntimeException("Failed to move thumbnail: {$thumbnailPath}");
            }
        }
    }

    /**
     * Sync actresses for video
     * 
     * @param string $videoId
     * @param array $actresses
     * @return void
     */
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

        $this->syncTagsByActress($video, $actresses);
    }

    /**
     * Sync tags for video by actresses
     * 
     * @param Video $video
     * @param array $actresses
     * @return void
     */
    public function syncTagsByActress(Video $video, array $actresses): void
    {
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

    /**
     * Search videos by keyword
     * 
     * @param string $keyword
     * @return Collection
     */
    public function search(string $keyword)
    {
        return $this->video->where('title', 'like', "%{$keyword}%")
            ->orderByDesc('latest_like')
            ->orderBy('title')
            ->limit(10)
            ->get(['id', 'title']);
    }

    /**
     * Increment like for video
     * 
     * @param string $videoId
     * @return bool
     */
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
        } catch (Throwable $e) {
            Log::error("Error incrementing like for video ID {$videoId}: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Get related videos
     * 
     * @param Video $video
     * @param int $limit
     * @return Collection
     */
    public function getRelatedVideos(Video $video, int $limit = 10)
    {
        $actressIds = $video->actresses->pluck('id')->toArray();
        $tagIds = $video->tags->pluck('id')->toArray();

        $relatedVideosWithTags = Video::whereHas('tags', function ($query) use ($tagIds) {
                $query->whereIn('tags.id', $tagIds);
            })
            ->where('id', '!=', $video->id)
            ->with(['thumbnails'])
            ->distinct()
            ->inRandomOrder()
            ->limit($limit)
            ->get();

        $relatedVideos = Video::whereHas('actresses', function ($query) use ($actressIds) {
                $query->whereIn('actresses.id', $actressIds);
            })
            ->where('id', '!=', $video->id)
            ->with(['thumbnails'])
            ->distinct()
            ->inRandomOrder()
            ->get()
            ->merge($relatedVideosWithTags)
            ->unique('id')
            ->take($limit);

        return $relatedVideos;
    }

    /**
     * Get random videos
     * 
     * @param int $limit
     * @return Collection
     */
    public function getRandomVideos(int $limit = 5)
    {
        return Video::with(['thumbnails'])
            ->inRandomOrder()
            ->limit($limit)
            ->get();
    }

    /**
     * Get videos for review
     * 
     * @param PathType $path
     * @return Collection
     */
    public function getVideosForReview(PathType $path): Collection
    {
        $reviewPath = storage_path('app/public/'.$path->value);
        $videos = scandir($reviewPath);

        if ($videos === false) {
            return collect();
        }

        return collect($videos)
            ->filter(fn ($file) => $file !== '.' && $file !== '..')
            ->map(function ($file) use ($path, $reviewPath) {
                $createdAt = filectime($reviewPath.'/'.$file);

                if ($createdAt === false) {
                    return null;
                }

                return (object) [
                    'title' => (string) str($file)->beforeLast('.mp4'),
                    'path' => $path->value,
                    'created_at' => date('Y-m-d H:i:s', $createdAt),
                    'is_download' => str($file)->endsWith('.mp4.crdownload'),
                ];
            })
            ->filter()
            ->sort(fn ($a, $b) => strtotime($b->created_at) <=> strtotime($a->created_at))
            ->values();
    }

    /**
     * Get all videos for terminal
     * 
     * @param array $filters
     * @return Collection
     */
    public function getAllForTerminal(array $filters = []): Collection
    {
        $tagSlugs = Arr::get($filters, 'tags', []);

        $query = Video::select('id', 'title')
            ->when(count($tagSlugs), function ($query) use ($tagSlugs) {
                $query->whereHas('tags', function ($query) use ($tagSlugs) {
                    $query->whereIn('slug', $tagSlugs);
                }, '=', count($tagSlugs));
            });

        if ($keyword = ($filters['search'] ?? null)) {
            $query->where('title', 'like', "%{$keyword}%")
                ->orWhere('id', $keyword);
        }

        return $query->get();
    }
}
