<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('phrases_tags', function (Blueprint $table) {
            $table->foreignId('phrase_id')->constrained('phrases')->cascadeOnDelete();
            $table->foreignId('tag_id')->constrained('tags')->cascadeOnDelete();

            $table->unique(['phrase_id', 'tag_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('phrases_tags');
    }
};
