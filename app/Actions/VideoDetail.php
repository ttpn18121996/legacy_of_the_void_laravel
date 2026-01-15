<?php

namespace App\Actions;

use App\Services\VideoService;

class VideoDetail
{
    public function __construct(
        protected VideoService $videoService,
    ) {}

    public function get($id)
    {
        $video = $this->videoService->find($id);
        $actresses = $video->actresses;
        $tags = $video->tags;

        $actressesList = '';
        foreach ($actresses as $actress) {
            $actressesList .= "#{$actress->id} - {$actress->name}<br>";
        }

        $tagsList = '';
        foreach ($tags as $tag) {
            $tagsList .= "{$tag->slug}<br>";
        }

        return <<<HTML
            <p>Video Details:</p><br>
            <table class="terminal__list">
                <tr><th>ID:</th><td>{$video->id}</td></tr>
                <tr><th>Title:</th><td>{$video->title}</td></tr>
                <tr><th>Likes:</th><td>{$video->like}</td></tr>
                <tr><th>Actresses:</th><td><ul>{$actressesList}</ul></td></tr>
                <tr><th>Tags:</th><td><ul>{$tagsList}</ul></td></tr>
                <tr><th>Created At:</th><td>{$video->created_at}</td></tr>
            </table>
        HTML;
    }
}
