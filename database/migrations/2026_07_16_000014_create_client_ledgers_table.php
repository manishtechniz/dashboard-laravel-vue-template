<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('client_ledgers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_id')->constrained('clients')->onDelete('cascade');
            $table->foreignId('booking_id')->nullable()->constrained('bookings')->onDelete('set null');
            $table->foreignId('payment_id')->nullable()->constrained('payments')->onDelete('set null');
            $table->string('due_type');
            $table->string('advance_type');
            $table->decimal('due_amount', 10, 2);
            $table->decimal('advance_amount', 10, 2);
            $table->decimal('advance_after', 10, 2)->default(0.00);
            $table->decimal('due_after', 10, 2)->default(0.00);
            $table->string('action_for')->nullable();
            $table->string('description')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('client_ledgers');
    }
};
