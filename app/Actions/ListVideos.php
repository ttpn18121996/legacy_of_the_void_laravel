<?php

namespace App\Actions;

use App\Services\VideoService;

class ListVideos
{
    public function __construct(
        protected VideoService $videoService,
    ) {}

    public function get(array $filters = [])
    {
        $videos = $this->videoService->getAllForTerminal($filters);

        $rows = '';
        foreach ($videos as $video) {
            $rows .= "<tr><th>#{$video->id}</th><td>{$video->title}</td></tr>";
        }

        return <<<HTML
            <p>List all videos:</p><br>
            <table class="terminal__list">{$rows}</table>
        HTML;
    }

    public function tags(array $tags = [])
    {
        $videos = $this->videoService->getAllForTerminal(['tags' => $tags]);

        $rows = '';
        foreach ($videos as $video) {
            $rows .= "<tr><th>#{$video->id}</th><td>{$video->title}</td></tr>";
        }

        $tagsString = implode(', ', $tags);

        return <<<HTML
            <p>List all videos with tags:</p>
            <p>{$tagsString}</p><br>
            <table class="terminal__list">{$rows}</table>
        HTML;
    }
}
