<?php

use App\Models\Actress;
use App\Models\Tag;
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
        Schema::create('actress_tag', function (Blueprint $table) {
            $table->foreignIdFor(Tag::class);
            $table->foreignIdFor(Actress::class);
            $table->unique(['tag_id', 'actress_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('actress_tag');
    }
};
