<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            // Link an inquiry to a registered customer (nullable: guests may still book).
            $table->foreignId('user_id')->nullable()->after('id')->constrained('users')->nullOnDelete();

            // Pricing / coupon snapshot captured at submission time.
            $table->foreignId('coupon_id')->nullable()->after('destination_id')->constrained('coupons')->nullOnDelete();
            $table->string('coupon_code')->nullable()->after('coupon_id');
            $table->decimal('subtotal', 12, 2)->nullable()->after('coupon_code');
            $table->decimal('discount_amount', 12, 2)->default(0)->after('subtotal');
            $table->decimal('total', 12, 2)->nullable()->after('discount_amount');
        });
    }

    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropConstrainedForeignId('user_id');
            $table->dropConstrainedForeignId('coupon_id');
            $table->dropColumn(['coupon_code', 'subtotal', 'discount_amount', 'total']);
        });
    }
};
