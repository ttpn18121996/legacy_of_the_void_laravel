<?php

namespace App\Http\Controllers;

use App\Services\VideoService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;

class ReviewController extends Controller
{
    public function __construct(
        private VideoService $videoService,
    ) {}

    public function index()
    {
        $reviewPath = storage_path('app/public/reviews');
        $videos = scandir($reviewPath);

        if ($videos !== false) {
            $videos = collect($videos)
                ->filter(fn ($file) => $file !== '.' && $file !== '..')
                ->map(fn ($file) => ((object) [
                    'title' => (string) str($file)->basename('.mp4'),
                    'path' => 'reviews',
                ]))
                ->values();
        }

        return view('reviews.index', [
            'videos' => $videos,
            'title' => 'Review Videos',
        ]);
    }

    public function approved()
    {
        $approvedPath = storage_path('app/public/approved');
        $videos = scandir($approvedPath);

        if ($videos !== false) {
            $videos = collect($videos)
                ->filter(fn ($file) => $file !== '.' && $file !== '..')
                ->map(fn ($file) => ((object) [
                    'title' => (string) str($file)->basename('.mp4'),
                    'path' => 'approved',
                ]))
                ->values();
        }

        return view('reviews.index', [
            'videos' => $videos,
            'title' => 'Approved Videos',
        ]);
    }

    public function show(Request $request)
    {
        return view('reviews.watch', [
            'title' => $request->query('title'),
            'path' => $request->query('path'),
        ]);
    }

    public function store(Request $request)
    {
        $title = $request->input('title');
        $path = $request->input('path');
        $pathTo = $path === 'reviews' ? 'approved' : 'reviews';

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

        $redirectTo = $path === 'reviews' ? 'reviews.index' : 'reviews.approved';

        return to_route($redirectTo);
    }

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

    public function destroy(Request $request)
    {
        $result = $this->videoService->moveToTrash($request->query('title'), $request->query('path'));

        if (! $result) {
            return back()->withErrors([
                'video' => 'Failed to move video to trash.',
            ]);
        }

        return to_route('reviews.index');
    }
}
