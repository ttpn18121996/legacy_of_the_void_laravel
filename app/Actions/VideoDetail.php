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

    public function watch($id)
    {
        $video = $this->videoService->find($id);
        if (!$video) {
            return '<p>Video not found.</p>';
        }

        $videoSrc = $this->getVideoSrcFromPath('videos', $video->title);

        return <<<HTML
            <p>Now watching: <strong>#{$video->id} - {$video->title}</strong></p>
            <div class="terminal__video-player">
                <video controls autoplay preload="auto">
                    <source src="{$videoSrc}" type="video/mp4">
                    Your browser does not support the video tag.
                </video>
            </div>
        HTML;
    }

    public function review($title)
    {
        $videoPath = storage_path("app/public/reviews/{$title}.mp4");
        $approvedPath = storage_path("app/public/approved/{$title}.mp4");
        $videoSrc = null;

        if (file_exists($videoPath)) {
            $videoSrc = $this->getVideoSrcFromPath('reviews', $title);
        } elseif (file_exists($approvedPath)) {
            $videoSrc = $this->getVideoSrcFromPath('approved', $title);
        }

        if (!$videoSrc) {
            return '<p>Video not found in review or approved folders.</p>';
        }

        return <<<HTML
            <p>Reviewing video: <strong>{$title}</strong></p>
            <div class="terminal__video-player">
                <video controls autoplay preload="auto">
                    <source src="{$videoSrc}" type="video/mp4">
                    Your browser does not support the video tag.
                </video>
            </div>
        HTML;
    }

    private function getVideoSrcFromPath(string $path, string $title): string
    {
        $videoPath = storage_path("app/public/{$path}/{$title}.mp4");

        if (! file_exists($videoPath)) {
            return '';
        }

        return route('videos.stream', ['file_name' => $title, 'path' => $path]);
    }
}
