<?php

namespace App\Services;

use App\Models\Actress;
use App\Models\Tag;
use App\Models\Video;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;
use LogicException;
use RuntimeException;

class ActressService
{
    public function __construct(
        protected Actress $actress,
    ) {}

    public function paginate(array $filters = [])
    {
        $q = Arr::get($filters, 'q');
        $tagSlugs = Arr::get($filters, 'tags', []);
        $sortMode = Arr::get($filters, 'sort_mode');
        $sortBy = Arr::get($filters, 'sort_by');
        $actressIds = [];

        if ($q) {
            $actressIds = Tag::where('title', 'like', "%{$q}%")
                ->join('actress_tag', 'tags.id', '=', 'actress_tag.tag_id')
                ->pluck('actress_tag.actress_id');
        }

        $actresses = Actress::with(['tags'])
             ->when($q, function ($query) use ($q, $actressIds) {
                $query->where('name', 'like', "%{$q}%")
                    ->orWhere('another_name', 'like', "%{$q}%")
                    ->orWhereIn('id', $actressIds);
            });

        if ($sortMode === 'without') {
            if ($sortBy === 'thumbnail') {
                $actresses->whereNull('thumbnail_path');
            } elseif ($sortBy === 'tags') {
                $actresses->whereDoesntHave('tags');
            } elseif ($sortBy === 'videos') {
                $actresses->whereDoesntHave('videos');
            }
        } else {
            $actresses->when(count($tagSlugs), function ($query) use ($tagSlugs) {
                $query->whereHas('tags', function ($query) use ($tagSlugs) {
                    $query->whereIn('slug', $tagSlugs);
                }, '=', count($tagSlugs));
            })->orderBy('name');
        }

        return $actresses->paginate(20)
            ->onEachSide(2)
            ->withQueryString();
    }

    public function create(array $data): bool
    {
        $name = Arr::get($data, 'name');

        try {
            $existingActress = $this->actress->where('name', $name)->first();
            if ($existingActress) {
                throw new LogicException('Actress with the same name already exists.');
            }

            $this->actress->name = $name;
            $this->actress->another_name = Arr::get($data, 'another_name') ?? $name;
            $this->actress->save();
            $this->actress->tags()->attach(Arr::get($data, 'tags', []));

            $code = Artisan::call('app:sync-actress-thumbnail', [
                '--name' => $name,
            ]);

            if ($code !== 0) {
                throw new RuntimeException('Failed to sync actress thumbnail.');
            }

            return true;
        } catch (\Exception $e) {
            Log::error('Error creating actress: ' . $e->getMessage());

            return false;
        }
    }

    public function find(int $id)
    {
        return $this->actress->with(['tags'])->where('id', $id)->firstOrFail();
    }

    public function update(array $data, int $id): bool
    {
        $actress = $this->find($id);
        $name = Arr::get($data, 'name');

        $actress->name = $name;
        $actress->another_name = Arr::get($data, 'another_name') ?? $name;
        $actress->save();
        $actress->tags()->sync(Arr::get($data, 'tags', []));

        return true;
    }

    public function delete(int $id): bool
    {
        $actress = $this->find($id);

        if (file_exists(storage_path('app/public/' . $actress->getPath()))) {
            unlink(storage_path('app/public/' . $actress->getPath()));
        }

        $actress->tags()->detach();
        $actress->videos()->detach();
        $actress->delete();

        return true;
    }

    public function getOptions(?Video $video = null): array
    {
        $selectedIds = [];

        if ($video) {
            $selectedIds = $video->actresses->pluck('id')->toArray();
        }

        return $this->actress->orderBy('name')->get()
            ->map(fn ($actress) => [
                'value' => $actress->id,
                'label' => $actress->name != $actress->another_name
                    ? "{$actress->name} ({$actress->another_name})"
                    : $actress->name,
                'selected' => in_array($actress->id, $selectedIds),
            ])
            ->toArray();
    }

    public function search(string $keyword)
    {
        return $this->actress->where('name', 'like', "%{$keyword}%")
            ->orWhere('another_name', 'like', "%{$keyword}%")
            ->orderBy('name')
            ->limit(5)
            ->get(['id', 'name', 'another_name']);
    }
}
