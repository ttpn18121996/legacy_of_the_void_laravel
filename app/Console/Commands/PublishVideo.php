<?php

namespace App\Console\Commands;

use App\Models\Actress;
use App\Models\Tag;
use App\Models\Video;
use App\Models\VideoThumbnail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Throwable;

class PublishVideo extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:publish-video 
                            {--name= : The name of the video, if not provided, takes multiple videos}
                            {--limit=10 : The number of videos to publish}
                            {--thumbnails=8 : The number of thumbnails}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Extract thumbnails from videos using ffmpeg';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $videosPath = storage_path('app/public/approved');
        $publishedDir = storage_path('app/public/videos');
        $thumbnailsCount = (int) $this->option('thumbnails');

        if ($name = $this->option('name')) {
            $videoNames = ["{$name}.mp4"];
        } else {
            $videoNames = collect(scandir($videosPath))
                ->filter(fn ($file) => Str::endsWith($file, ['.mp4']))
                ->take((int) $this->option('limit'))
                ->values()
                ->all();
        }

        foreach ($videoNames as $video) {
            $this->info("Processing: {$video}");

            $videoPath = "$videosPath/$video";
            $videoNameWithoutExt = pathinfo($video, PATHINFO_FILENAME);
            $thumbnailDir = storage_path("app/public/thumbnails/{$videoNameWithoutExt}");

            if (! file_exists($thumbnailDir)) {
                mkdir($thumbnailDir, 0777, true);
            }

            $ffprobeCmd = "ffprobe -v error -select_streams v:0 -show_entries stream=width,height,duration -of default=noprint_wrappers=1 \"$videoPath\"";
            $ffprobeOutput = shell_exec($ffprobeCmd);
            preg_match('/width=(\d+)/', $ffprobeOutput, $widthMatch);
            preg_match('/height=(\d+)/', $ffprobeOutput, $heightMatch);
            preg_match('/duration=([\d\.]+)/', $ffprobeOutput, $durationMatch);

            $width = $widthMatch[1] ?? null;
            $height = $heightMatch[1] ?? null;
            $durationInSeconds = isset($durationMatch[1]) ? floor((float) $durationMatch[1]) : null;

            if (! $width || ! $height || ! $durationInSeconds) {
                $this->error("Failed to extract metadata for $video");
                Log::error("Failed to extract metadata for $video");

                continue;
            }

            $dimensions = "{$width} x {$height}";
            $interval = $durationInSeconds / ($thumbnailsCount + 1);

            for ($i = 1; $i <= $thumbnailsCount; $i++) {
                $timestamp = gmdate('H:i:s', (int) ($interval * $i));
                $outputPath = "$thumbnailDir/thumbnail_$i.png";

                $cmd = "ffmpeg -ss $timestamp -i \"$videoPath\" -frames:v 1 -q:v 2 \"$outputPath\"";
                shell_exec($cmd);

                $this->info("Created thumbnail_$i.png at $timestamp");
            }

            // Move the video to the published folder
            if (! file_exists($publishedDir)) {
                mkdir($publishedDir, 0777, true);
            }

            $destinationPath = "$publishedDir/$video";

            if (! rename($videoPath, $destinationPath)) {
                $this->error("Failed to move $video to published folder.");
                Log::error("Failed to move $video to published folder.");

                continue;
            }

            $this->info("Moved $video to published folder.");

            DB::beginTransaction();

            try {
                $videoModel = Video::create([
                    'title' => $videoNameWithoutExt,
                    'path' => "videos/{$video}",
                    'duration' => gmdate('H:i:s', $durationInSeconds),
                    'dimensions' => $dimensions,
                ]);

                $now = now();

                VideoThumbnail::insert(collect()
                    ->range(1, $thumbnailsCount)
                    ->map(fn ($i) => [
                        'video_id' => $videoModel->id,
                        'path' => "thumbnails/{$videoNameWithoutExt}/thumbnail_$i.png",
                        'is_default' => $i === 1,
                        'created_at' => $now,
                        'updated_at' => $now,
                    ])
                    ->toArray());

                $tags = [];

                $actressNames = Str::of($videoNameWithoutExt)
                    ->before(' - ')
                    ->explode(',')
                    ->map(fn ($name) => trim($name))
                    ->filter()
                    ->toArray();

                if (count($actressNames) === 2) {
                    $tags[] = Tag::where('title', 'threesome')->first()?->id;
                } elseif (count($actressNames) === 3) {
                    $tags[] = Tag::where('title', 'foursome')->first()?->id;
                } elseif (count($actressNames) > 3) {
                    $tags[] = Tag::where('title', 'gangbang')->first()?->id;
                }

                $actresses = Actress::whereIn('name', $actressNames)->with(['tags'])->get();
                $videoModel->actresses()->attach($actresses->pluck('id')->toArray());

                $tags = $actresses->flatMap(fn ($actress) => $actress->tags->pluck('id'))->merge($tags)->unique()->toArray();
                $videoModel->tags()->attach($tags);

                DB::commit();

                $this->info("[{$videoNameWithoutExt}] inserted successfully.");
            } catch (Throwable $e) {
                DB::rollBack();

                $message = "Failed to insert [{$videoNameWithoutExt}] into database. ".$e->getMessage();

                $this->error($message);
                Log::error($message);
            }
        }

        $this->info('Videos published.');
    }
}
