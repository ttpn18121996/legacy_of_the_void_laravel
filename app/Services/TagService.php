<?php

namespace App\Services;

use App\Models\Tag;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

class TagService
{
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

        return true;
    }

    public function getOptions()
    {
        return $this->tag
            ->orderBy('title')->get()
            ->map(fn ($tag) => ['value' => $tag->id, 'label' => "#{$tag->title}"])
            ->toArray();
    }

    public function search(string $keyword)
    {
        return Tag::where('title', 'like', "%{$keyword}%")
            ->orderBy('title')
            ->get();
    }
}
