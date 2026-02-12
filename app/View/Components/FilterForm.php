<?php

namespace App\View\Components;

use App\Models\Tag;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\View\Component;

class FilterForm extends Component
{
    public Collection $tags;
    public Collection $filteredTags;
    public array $currentFilters = [];

    /**
     * Create a new component instance.
     */
    public function __construct(?Collection $filteredTags) {
        $this->filteredTags = $filteredTags ?? collect([]);
        $this->tags = $this->getTags();
    }

    private function getTags(): Collection
    {
        $tags = request()->query('tags', []);
        $this->currentFilters = $tags;

        return Cache::remember('tags', 60, function () {
            return Tag::orderBy('title')->get();
        });
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.filter-form');
    }
}
