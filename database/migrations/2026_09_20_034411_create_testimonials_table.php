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
        Schema::create('testimonials', function (Blueprint $table) {
            $table->id();
            
            // Foreign Keys
            $table->foreignId('club_id')->constrained('clubs')->cascadeOnDelete();
            $table->foreignId('client_id')->constrained('clients')->cascadeOnDelete();

            // Testimonial Fields 
            $table->unsignedTinyInteger('rating')->default(5); // 1 to 5 scale
            $table->string('title')->nullable();
            $table->text('comment');
            $table->boolean('is_published')->default(true);
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('testimonials');
    }
};