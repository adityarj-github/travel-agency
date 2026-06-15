<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            // Flags bookings created by the admin "payment link" generator so they
            // can be listed/managed separately from customer-submitted bookings.
            $table->boolean('is_payment_link')->default(false)->after('payment_token');

            // Phone is no longer compulsory on the booking form, so allow null.
            $table->string('phone')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropColumn('is_payment_link');
            $table->string('phone')->nullable(false)->change();
        });
    }
};
