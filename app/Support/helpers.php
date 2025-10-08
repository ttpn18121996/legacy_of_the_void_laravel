<?php

if (! function_exists('get_filter_tag_url')) {
    function get_filter_tag_url(string $tagSlug, bool $addTag = true): string
    {
        $currentTags = collect(request()->query('tags', []));

        if (! $addTag) {
            $currentTags = $currentTags->filter(fn ($tag) => $tag !== $tagSlug)->values();
        } else {
            $currentTags = $currentTags->push($tagSlug);
        }

        return route('videos.index', [
            'tags' => $currentTags->toArray(),
        ]);
    }
}

if (! function_exists('route_path_only')) {
    function route_path_only(string $name, $parameters = []): string
    {
        $url = route($name, $parameters);

        return parse_url($url)['path'];
    }
}
