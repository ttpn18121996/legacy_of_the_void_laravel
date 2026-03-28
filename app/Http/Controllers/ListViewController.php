<?php

namespace App\Http\Controllers;

use App\Enums\PathType;
use App\Services\VideoService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;

class ListViewController extends Controller
{
    public function __construct(
        protected VideoService $videoService,
    ) {}

    /**
     * Display a listing of videos based on the specified path.
     */
    public function index(Request $request)
    {
        $path = $request->query('path', PathType::REVIEW->value);
        $videos = $this->videoService->getVideosForReview(PathType::from($path));

        return view('list-view.index', [
            'videos' => $videos,
            'title' => PathType::from($path)->value,
        ]);
    }

    /**
     * Display a specific video for review.
     */
    public function show(Request $request)
    {
        return view('list-view.watch', [
            'title' => $request->query('title'),
            'path' => $request->query('path'),
        ]);
    }

    /**
     * Handle approving or rejecting a video.
     */
    public function store(Request $request)
    {
        $title = $request->input('title');
        $path = $request->input('path');

        if (! in_array($path, PathType::reviewable())) {
            return back()->withErrors([
                'video' => 'Invalid path type.',
            ]);
        }

        try {
            $pathTo = match ($path) {
                PathType::REVIEW->value => PathType::APPROVED->value,
                PathType::APPROVED->value => PathType::REVIEW->value,
                PathType::TRASH->value => PathType::REVIEW->value,
                default => throw new Exception('Invalid path type.'),
            };
        } catch (Exception $e) {
            return back()->withErrors([
                'video' => $e->getMessage(),
            ]);
        }

        $videoPath = storage_path("app/public/{$path}/{$title}.mp4");
        $pointPath = storage_path("app/public/{$pathTo}/{$title}.mp4");

        if (! file_exists($videoPath)) {
            return back()->withErrors([
                'video' => 'Video not found.',
            ]);
        }

        if (! rename($videoPath, $pointPath)) {
            return back()->withErrors([
                'video' => 'Failed to move video.',
            ]);
        }

        return redirect(route('list-view.index', ['path' => $path]));
    }

    /**
     * Handle publishing a video.
     */
    public function update(Request $request)
    {
        $title = $request->input('title');

        $exitCode = Artisan::call('app:publish-video', [
            '--name' => $title,
        ]);

        if ($exitCode === 0) {
            return response()->json([
                'success' => true,
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Failed to publish video.',
        ], 500);
    }

    /**
     * Handle deleting a video. Move to trash.
     */
    public function destroy(Request $request)
    {
        $path = $request->query('path', PathType::REVIEW->value);
        $title = $request->query('title');

        if (
            $request->query('permanent') === '1'
            && $path === PathType::TRASH->value
            && file_exists(storage_path("app/public/trash/{$title}.mp4"))
        ) {
            $result = unlink(storage_path("app/public/trash/{$title}.mp4"));
        } else {
            $result = $this->videoService->moveToTrash($title, $path);
        }

        if (! $result) {
            return back()->withErrors([
                'video' => 'Failed to move video to trash.',
            ]);
        }

        return redirect(route('list-view.index', ['path' => $path]));
    }
}
