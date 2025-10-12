<?php

namespace App\Console\Commands;

use App\Models\Actress;
use Illuminate\Console\Command;

class SyncActressThumbnail extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:sync-actress-thumbnail {--name=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync actress thumbnails from available images in storage';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        if ($name = $this->option('name')) {
            return $this->syncForOne($name);
        }

        return $this->syncForAll();
    }

    protected function syncForOne($name)
    {
        $actress = Actress::where('name', $name)->first();
        if (! $actress) {
            $this->error("Actress with name '{$name}' not found.");
            return static::FAILURE;
        }

        $slug = str($actress->name)->slug()->toString();
        $availableThumbnails = collect(scandir(storage_path('app/public/actresses')))
            ->filter(fn ($file) => !in_array($file, ['.', '..', 'thubmnail-default.jpg']))
            ->mapWithKeys(fn ($file) => [str(str($file)->beforeLast('.'))->slug()->toString() => $file]);

        if ($availableThumbnails->has($slug)) {
            $actress->thumbnail_path = 'actresses/' . $availableThumbnails->get($slug);
            $actress->save();
            $this->info("Updated thumbnail for actress: {$actress->name}");
        } else {
            $this->info("No available thumbnail found for actress: {$actress->name}");
        }

        return static::SUCCESS;
    }

    protected function syncForAll()
    {
        $actressesWithoutThumbnail = Actress::whereNull('thumbnail_path')->get();
        $availableThumbnails = collect(scandir(storage_path('app/public/actresses')))
            ->filter(fn ($file) => !in_array($file, ['.', '..', 'thubmnail-default.jpg']))
            ->mapWithKeys(fn ($file) => [str(str($file)->beforeLast('.'))->slug()->toString() => $file]);

        foreach ($actressesWithoutThumbnail as $actress) {
            $slug = str($actress->name)->slug()->toString();
            if ($availableThumbnails->has($slug)) {
                $actress->thumbnail_path = 'actresses/' . $availableThumbnails->get($slug);
                $actress->save();
                $this->info("Updated thumbnail for actress: {$actress->name}");
            }
        }

        return static::SUCCESS;
    }
}
