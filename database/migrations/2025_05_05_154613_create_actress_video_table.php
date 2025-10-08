<?php

use App\Models\Actress;
use App\Models\Video;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('actress_video', function (Blueprint $table) {
            $table->foreignIdFor(Actress::class);
            $table->foreignIdFor(Video::class);
            $table->unique(['actress_id', 'video_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('actress_video');
    }
};
