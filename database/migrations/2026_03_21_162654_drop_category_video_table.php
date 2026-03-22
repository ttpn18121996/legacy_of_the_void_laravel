<?php

use App\Models\Category;
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
        Schema::dropIfExists('category_video');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::create('category_video', function (Blueprint $table) {
            $table->foreignIdFor(Category::class);
            $table->foreignIdFor(Video::class);
            $table->unique(['category_id', 'video_id']);
        });
    }
};
