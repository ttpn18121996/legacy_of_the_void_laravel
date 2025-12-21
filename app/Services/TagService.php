<?php

namespace App\Services;

use App\Models\Tag;
use App\Models\Video;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class TagService
{
    private const TAG_OPTIONS_CACHE_KEY = 'tag_options';

    public function __construct(
        protected Tag $tag,
    ) {}

    public function all(array $filters = [])
    {
        $q = Arr::get($filters, 'q');

        return Tag::when($q, function ($query) use ($q) {
            $query->where('title', 'like', "%{$q}%");
        })
        ->orderBy('title')
        ->get();
    }

    public function find(string $id): Tag
    {
        return Tag::findOrFail($id);
    }

    public function create(array $data): bool
    {
        $title = Str::lower(Arr::get($data, 'title'));

        $tag = new Tag();
        $tag->title = $title;
        $tag->slug = Str::slug($title);
        $tag->save();

        $this->checkAndUpdateCache();
        
        return true;
    }

    public function update(array $data, string $id): Tag
    {
        $title = Str::lower(Arr::get($data, 'title'));

        $tag = $this->find($id);
        $tag->forceFill([
            'title' => $title,
            'slug' => Str::slug($title),
        ]);
        $tag->save();

        return $tag->fresh();
    }

    public function delete(string $id): true
    {
        $tag = $this->find($id);
        $tag->videos()->detach();
        $tag->actresses()->detach();
        $tag->delete();

        $this->checkAndUpdateCache();

        return true;
    }

    protected function checkAndUpdateCache(): void
    {
        Cache::forget(static::TAG_OPTIONS_CACHE_KEY);
        Cache::rememberForever(static::TAG_OPTIONS_CACHE_KEY, function () {
            return $this->tag->orderBy('title')->get();
        });
    }

    public function getOptions(?Video $video = null): array
    {
        $selectedIds = [];

        $tags = $this->getOptionsInCache();

        if ($video) {
            $selectedIds = $video->tags->pluck('id')->toArray();
        }

        return $tags
            ->map(fn ($tag) => [
                'value' => $tag->id,
                'label' => "#{$tag->title}",
                'selected' => in_array($tag->id, $selectedIds),
            ])
            ->toArray();
    }

    protected function getOptionsInCache(): Collection
    {
        return Cache::rememberForever(static::TAG_OPTIONS_CACHE_KEY, function () {
            return $this->tag->orderBy('title')->get();
        });
    }

    public function search(string $keyword)
    {
        return Tag::where('title', 'like', "%{$keyword}%")
            ->orderBy('title')
            ->get();
    }
}
