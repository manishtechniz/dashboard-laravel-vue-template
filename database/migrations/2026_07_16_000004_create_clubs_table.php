<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('clubs', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('address')->nullable();
            $table->string('city')->nullable();
            $table->string('logo')->nullable();
            $table->decimal('average_rating', 3, 2)->default(0.00);
            $table->unsignedInteger('review_count')->default(0);
            $table->decimal('rating_5_percent', 5, 2)->default(0);
            $table->decimal('rating_4_percent', 5, 2)->default(0);
            $table->decimal('rating_3_percent', 5, 2)->default(0);
            $table->decimal('rating_2_percent', 5, 2)->default(0);
            $table->decimal('rating_1_percent', 5, 2)->default(0);

            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('clubs');
    }
};
