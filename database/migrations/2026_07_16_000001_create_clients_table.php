<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('clients', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('email')->nullable()->unique();
            $table->string('phone')->nullable()->unique();
            $table->string('avatar')->nullable();
            $table->string('age')->nullable();
            $table->string('gender')->nullable();
            $table->string('password')->nullable();
            $table->foreignId('role_id')->nullable()->constrained('mobile_app_roles')->onDelete('set null');
            $table->string('google_id')->nullable()->unique();
            $table->string('fcm_token')->nullable();
            $table->boolean('is_active')->default(true);
            $table->rememberToken();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('clients');
    }
};
