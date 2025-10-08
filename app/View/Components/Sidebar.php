<?php

namespace App\View\Components;

use App\Models\Tag;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;
use Illuminate\View\Component;

class Sidebar extends Component
{
    public Collection $tags;

    public array $currentFilters = [];

    /**
     * Create a new component instance.
     */
    public function __construct()
    {
        $this->tags = $this->getTags();
    }

    private function getTags(): Collection
    {
        $tags = request()->query('tags', []);

        if (count($tags) != count($this->currentFilters)) {
            $this->currentFilters = $tags;
            $newTags = Tag::whereNotIn('slug', $tags)->orderBy('title')->get();
            cache()->put('tags', $newTags, 60);

            return $newTags;
        }

        return cache()->get('tags', Tag::all());
    }

    public function getLinkForTag($slug): string
    {
        return route('videos.index', [
            'tags' => array_merge(request()->query('tags', []), [$slug]),
        ]);
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.sidebar');
    }
}
