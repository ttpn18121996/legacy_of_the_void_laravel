<?php

use App\Models\Tag;
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
        Schema::create('tag_video', function (Blueprint $table) {
            $table->foreignIdFor(Tag::class);
            $table->foreignIdFor(Video::class);
            $table->unique(['tag_id', 'video_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tag_video');
    }
};
