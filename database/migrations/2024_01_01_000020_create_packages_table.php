<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('packages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('destination_id')->nullable()->constrained('destinations')->nullOnDelete();
            $table->string('title');
            $table->string('slug')->unique();
            $table->string('category')->nullable();        // e.g. Adventure, Honeymoon, Family
            $table->string('package_type')->nullable();    // e.g. Domestic, International
            $table->string('tour_type')->nullable();       // e.g. Group, Private
            $table->decimal('price', 12, 2)->default(0);
            $table->decimal('discount_price', 12, 2)->nullable();
            $table->unsignedSmallInteger('duration_days')->default(1);
            $table->unsignedSmallInteger('duration_nights')->default(0);
            $table->string('location')->nullable();
            $table->string('main_image')->nullable();
            $table->text('short_description')->nullable();
            $table->longText('description')->nullable();
            $table->json('itinerary')->nullable();          // [{day, title, detail}]
            $table->json('inclusions')->nullable();         // ["..."]
            $table->json('exclusions')->nullable();         // ["..."]
            $table->longText('terms')->nullable();
            $table->json('available_dates')->nullable();    // ["2026-07-01", ...]
            $table->unsignedInteger('max_people')->nullable();
            $table->boolean('is_featured')->default(false);
            $table->boolean('is_active')->default(true);
            $table->unsignedBigInteger('views')->default(0);
            $table->string('meta_title')->nullable();
            $table->text('meta_description')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['is_active', 'is_featured']);
            $table->index('category');
            $table->index('package_type');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('packages');
    }
};
