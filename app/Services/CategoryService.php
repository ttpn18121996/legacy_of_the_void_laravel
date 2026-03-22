<?php

namespace App\Services;

use App\Models\Category;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

class CategoryService
{
    public function __construct(
        protected Category $category,
    ) {}

    public function paginate()
    {
        return $this->category->with('tags')->paginate(20)
            ->onEachSide(2)
            ->withQueryString();
    }

    public function create(array $data): Category
    {
        $this->category->title = Str::lower(Arr::get($data, 'title'));
        $this->category->slug = Str::slug($this->category->title);
        $this->category->save();

        if (Arr::has($data, 'tags')) {
            $this->category->tags()->sync($data['tags']);
        }

        return $this->category->fresh();
    }
    
    public function find(int $id): ?Category
    {
        return $this->category->find($id);
    }
    
    public function findBySlug(string $slug): ?Category
    {
        return $this->category->where('slug', $slug)->first();
    }

    public function update(array $data, int $id): ?Category
    {
        $category = $this->find($id);

        if (is_null($category)) {
            return null;
        }

        $category->title = Str::lower(Arr::get($data, 'title'));
        $category->slug = Str::slug($category->title);
        $category->save();

        if (Arr::has($data, 'tags')) {
            $category->tags()->sync($data['tags']);
        }

        return $category->refresh();
    }

    public function delete(string $id)
    {
        $category = $this->find($id);

        if (is_null($category)) {
            return null;
        }

        $category->tags()->detach();
        $category->delete();

        return true;
    }
}
