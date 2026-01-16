<?php

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
        Schema::create('songs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('title');
            $table->string('artist')->nullable();
            $table->string('album')->nullable();
            $table->string('genre')->nullable();
            $table->integer('year')->nullable();
            $table->integer('duration')->default(0)->comment('in seconds');
            $table->string('file_path');
            $table->unsignedBigInteger('file_size')->default(0)->comment('in bytes');
            $table->string('cover_path')->nullable();
            $table->unsignedInteger('play_count')->default(0);
            $table->timestamps();

            // Indexes
            $table->index('user_id');
            $table->index('title');
            $table->index('artist');
            $table->index('album');
            $table->index('genre');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('songs');
    }
};
