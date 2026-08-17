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
        Schema::create('club_assets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('club_id')->constrained('clubs')->cascadeOnDelete();
            $table->string('batch_id')->nullable()->index();
            $table->string('title')->nullable();
            $table->string('file_name');
            $table->string('original_name')->nullable();
            $table->string('file_path');
            $table->string('file_type')->default('image')->index(); // 'image' or 'video'
            $table->string('mime_type')->nullable();
            $table->unsignedBigInteger('file_size')->nullable(); // size in bytes
            $table->unsignedInteger('width')->nullable();
            $table->unsignedInteger('height')->nullable();
            $table->string('duration')->nullable(); // video duration in seconds or hh:mm:ss
            $table->boolean('is_active')->default(true);
            $table->unsignedBigInteger('created_by')->nullable();
            $table->timestamps();

            $table->index(['club_id', 'file_type', 'is_active']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('club_assets');
    }
};
