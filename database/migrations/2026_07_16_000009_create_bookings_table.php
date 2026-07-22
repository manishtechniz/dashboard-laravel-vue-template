<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_id')->nullable()->constrained('clients')->onDelete('set null');
            $table->foreignId('table_id')->nullable()->constrained('tables')->onDelete('set null');
            $table->foreignId('event_id')->nullable()->constrained('events')->onDelete('set null');

            // Sceenshot related columns
            $table->string('client_name')->nullable();
            $table->string('client_phone')->nullable();
            $table->string('client_email')->nullable();

            // Discount related columns.
            $table->enum('discount_type', ['percentage', 'fixed'])->nullable();
            $table->decimal('discount_code', 50)->nullable();
            $table->string('discount_source', 100)->nullable();
            $table->string('discount_note', 100)->nullable();
            $table->decimal('discount_amount', 10, 2)->default(0.00);
            $table->decimal('max_discount_amount', 10, 2)->nullable();

            // Tax
            $table->integer('tax_rate')->default(0.00);
            $table->decimal('tax_amount', 10, 2)->default(0.00);
            $table->decimal('total_amount_excl_tax', 10, 2)->default(0.00);
            $table->decimal('total_amount_incl_tax', 10, 2)->default(0.00);

            $table->string('payment_status', 100)->nullable();
            $table->string('payment_gateway', 100)->nullable();
            $table->string('payment_method', 100)->nullable();

            $table->date('booking_date');

            $table->time('start_time');
            $table->time('end_time');

            $table->integer('guest_count');

            $table->string('status')->default('pending'); // pending, confirmed, cancelled, checked_in
            $table->text('special_requests')->nullable();
            $table->string('qr_code')->nullable();
            $table->text('remark')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
