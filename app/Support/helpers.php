<?php

use Illuminate\Support\Arr;

if (! function_exists('fill_input_to_sort')) {
    function fill_input_to_sort(): string
    {
        $html = '';
        $query = Arr::except(request()->query(), ['sort_by', 'destination', 'page', 'q']);

        foreach ($query as $key => $value) {
            if (is_array($value)) {
                foreach ($value as $item) {
                    $html .= "<input type=\"hidden\" name=\"{$key}[]\" value=\"{$item}\">";
                }
            } else {
                $html .= "<input type=\"hidden\" name=\"{$key}\" value=\"{$value}\">";
            }
        }

        return $html;
    }
}

if (! function_exists('force_rmdir')) {
    function force_rmdir(string $dir): bool
    {
        if (! is_dir($dir)) {
            return false;
        }

        $files = glob($dir . '/*', GLOB_MARK);
        foreach ($files as $file) {
            if (is_dir($file)) {
                if (! force_rmdir($file)) {
                    return false;
                }
            } elseif (! unlink($file)) {
                return false;
            }
        }

        if (! rmdir($dir)) {
            return false;
        }

        return true;
    }
}

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
