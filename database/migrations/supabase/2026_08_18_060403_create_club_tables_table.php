<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('club_tables', function (Blueprint $table) {
            $table->id();
            
            // Core Table Identification
            $table->string('table_number')->unique(); 
            $table->string('table_type');

            // Staff & Time
            $table->string('service_staff')->nullable();
            $table->string('booking_time')->nullable();

            // Status & Billing
            $table->string('status')->default('Empty');
            $table->decimal('bill_amount', 10, 2)->default(0.00);

            // Guest Details
            $table->string('guest_name')->nullable();
            
            // --- NEW: Club-Specific Fields ---
            $table->integer('guest_count')->nullable()->comment('Total number of people allowed/arrived');
            $table->boolean('is_vip')->default(false)->comment('Flags if this is a VIP table');
            $table->decimal('minimum_spend', 10, 2)->nullable()->comment('Required minimum spend for the table');
            $table->string('promoter_name')->nullable()->comment('Who booked or brought this guest in');

            // Locking Mechanism 
            $table->string('locked_by_name')->nullable();
            $table->boolean('is_locked')->default(false);
            $table->string('lock_password')->nullable();

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('club_tables');
    }
};