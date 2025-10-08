<?php

namespace App\Services;

use App\Models\Actress;
use App\Models\Tag;
use Illuminate\Support\Arr;

class ActressService
{
    public function __construct(
        private Actress $actress,
    ) {}

    public function paginate(array $filters = [])
    {
        $q = Arr::get($filters, 'q');
        $actressIds = [];

        if ($q) {
            $actressIds = Tag::where('title', 'like', "%{$q}%")
                ->join('actress_tag', 'tags.id', '=', 'actress_tag.tag_id')
                ->pluck('actress_tag.actress_id');
        }

        $actresses = Actress::when($q, function ($query) use ($q, $actressIds) {
            $query->where('name', 'like', "%{$q}%")
                ->orWhere('another_name', 'like', "%{$q}%")
                ->orWhereIn('id', $actressIds);
        })
            ->orderBy('name')
            ->paginate(20)
            ->onEachSide(2)
            ->withQueryString();

        return $actresses;
    }

    public function create(array $data): bool
    {
        $name = Arr::get($data, 'name');

        $this->actress->name = $name;
        $this->actress->another_name = Arr::get($data, 'another_name') ?? $name;
        $this->actress->save();
        $this->actress->tags()->attach(Arr::get($data, 'tags', []));

        return true;
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

    public function getOptions()
    {
        return $this->actress->orderBy('name')->get()
            ->map(fn ($actress) => [
                'value' => $actress->id,
                'label' => $actress->name != $actress->another_name
                    ? "{$actress->name} ({$actress->another_name})"
                    : $actress->name,
                'selected' => false,
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
