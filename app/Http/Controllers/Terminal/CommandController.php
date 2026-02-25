<?php

namespace App\Http\Controllers\Terminal;

use App\Actions\ListVideos;
use App\Actions\VideoDetail;
use App\Http\Controllers\Controller;
use App\Services\ActressService;
use App\Services\TagService;
use App\Services\VideoService;
use Illuminate\Http\Request;

class CommandController extends Controller
{
    public function __construct(
        protected ActressService $actressService,
        protected TagService $tagService,
        protected VideoService $videoService,
    ) {}

    public function __invoke(Request $request)
    {
        $command = $request->input('command');
        $arguments = $request->input('args', []);

        $listVideosAction = app()->make(ListVideos::class);
        $videoDetailAction = app()->make(VideoDetail::class);

        $content = match ($command) {
            'videos:list' => $listVideosAction->get(),
            'videos:detail' => isset($arguments[0]) ? $videoDetailAction->get($arguments[0]) : '<p>Please provide a video ID.</p>',
            'videos:tags' => $listVideosAction->tags($arguments),
            'videos:find' => $listVideosAction->get(['search' => $arguments[0] ?? null]),
            'videos:review' => $listVideosAction->reviewAndApproved(false),
            'videos:approved' => $listVideosAction->reviewAndApproved(true),
            'watch' => isset($arguments[0]) ? $videoDetailAction->watch($arguments[0]) : '<p>Please provide a video ID.</p>',
            'review' => isset($arguments[0]) ? $videoDetailAction->review($arguments[0]) : '<p>Please provide a video title.</p>',
            default => "<p>Command not found: <strong>{$command}</strong>. Type <code>help</code> to see available commands.</p>",
        };

        return response()->json([
            'success' => true,
            'content' => $content,
        ]);
    }
}
