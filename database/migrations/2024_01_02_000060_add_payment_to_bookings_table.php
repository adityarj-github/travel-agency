<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            // Payment state captured when the customer pays at booking time.
            $table->enum('payment_status', ['unpaid', 'paid', 'failed', 'refunded'])
                ->default('unpaid')->after('total');
            $table->string('payment_method')->nullable()->after('payment_status');

            // Per-booking secret so a pending booking's pay link works for guests
            // (bookings are id-bound, not slug-bound) without exposing other bookings.
            $table->string('payment_token', 64)->nullable()->after('payment_method');

            // Razorpay identifiers / verification trail.
            $table->string('razorpay_order_id')->nullable()->after('payment_token');
            $table->string('razorpay_payment_id')->nullable()->after('razorpay_order_id');
            $table->string('razorpay_signature')->nullable()->after('razorpay_payment_id');

            $table->decimal('amount_paid', 12, 2)->nullable()->after('razorpay_signature');
            $table->timestamp('paid_at')->nullable()->after('amount_paid');

            $table->index('payment_status');
        });
    }

    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropIndex(['payment_status']);
            $table->dropColumn([
                'payment_status', 'payment_method', 'payment_token',
                'razorpay_order_id', 'razorpay_payment_id', 'razorpay_signature',
                'amount_paid', 'paid_at',
            ]);
        });
    }
};
