<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Tag;
use App\Models\Video;
use App\Services\VideoService;
use Illuminate\Http\Request;

class VideoController extends Controller
{
    public function __construct(
        private VideoService $videoService,
    ) {}

    public function index(Request $request)
    {
        $tagSlugs = $request->query('tags', []);
        $filteredTags = Tag::whereIn('slug', $tagSlugs)->get();

        $videos = $this->videoService->paginateWithThumbnails($request->query());

        return view('videos.index', [
            'videos' => $videos,
            'filteredTags' => $filteredTags,
        ]);
    }

    public function show(string $id)
    {
        $video = $this->videoService->find($id);

        $categories = Category::orderBy('title')->get()
            ->map(fn ($category) => ['value' => $category->id, 'label' => $category->title])
            ->toArray();
        $selectedCategories = $video->categories->pluck('id')->toArray();

        return view('videos.show', [
            'video' => $video,
            'categories' => $categories,
            'selectedCategories' => $selectedCategories,
        ]);
    }

    public function stream(Request $request)
    {
        set_time_limit(0);

        $fileName = $request->query('file_name');
        $filePath = $request->query('path');
        $path = storage_path("app/public/{$filePath}/{$fileName}.mp4");

        abort_unless(file_exists($path), 404);

        $fileSize = filesize($path);
        $start = 0;
        $length = $fileSize;
        $end = $fileSize - 1;

        // Lấy thông tin range từ request
        if ($request->hasHeader('Range')) {
            $range = $request->header('Range');
            preg_match('/bytes=(\d+)-(\d+)?/', $range, $matches);

            $start = intval($matches[1]);

            if (isset($matches[2])) {
                $end = intval($matches[2]);
            }

            $length = $end - $start + 1;
            $status = 206; // Partial Content
            $headers = [
                'Content-Type' => 'video/mp4',
                'Content-Length' => $length,
                'Content-Range' => "bytes {$start}-{$end}/{$fileSize}",
                'Accept-Ranges' => 'bytes',
            ];
        } else {
            $status = 200;
            $headers = [
                'Content-Type' => 'video/mp4',
                'Content-Length' => $fileSize,
                'Accept-Ranges' => 'bytes',
            ];
        }

        return response()->stream(function () use ($path, $start, $length) {
            $stream = fopen($path, 'rb');
            fseek($stream, $start);

            $bufferSize = 1024 * 32; // 16KB
            while (! feof($stream) && ($length > 0)) {
                $read = ($length < $bufferSize) ? $length : $bufferSize;
                echo fread($stream, $read);
                $length -= $read;
                ob_flush();
                flush();
            }

            fclose($stream);
        }, $status, $headers);
    }

    public function updateActresses(Request $request)
    {
        $videoId = $request->input('video_id');
        $actresses = $request->input('actresses');

        $this->videoService->syncActresses($videoId, $actresses);

        return response()->json(['success' => true]);
    }

    public function updateCategories(Request $request)
    {
        $videoId = $request->input('video_id');
        $categories = $request->input('categories');

        $video = Video::find($videoId);
        $video->categories()->sync($categories);

        return response()->json(['success' => true]);
    }

    public function updateTags(Request $request)
    {
        $videoId = $request->input('video_id');
        $tags = $request->input('tags');

        $video = Video::find($videoId);
        $video->tags()->sync($tags);

        return response()->json(['success' => true]);
    }

    public function incrementLike(Request $request)
    {
        $videoId = $request->input('video_id');

        $result = $this->videoService->incrementLike($videoId);

        return response()->json(['success' => $result]);
    }
}
