<?php

namespace App\Models;

use App\Models\Concerns\HasSlug;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Package extends Model
{
    use HasFactory, SoftDeletes, HasSlug;

    protected static string $slugSource = 'title';

    protected $fillable = [
        'destination_id', 'title', 'slug', 'category', 'package_type', 'tour_type',
        'price', 'discount_price', 'duration_days', 'duration_nights', 'location',
        'main_image', 'short_description', 'description', 'itinerary',
        'inclusions', 'exclusions', 'terms', 'available_dates', 'max_people',
        'is_featured', 'is_active', 'views', 'meta_title', 'meta_description',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'discount_price' => 'decimal:2',
        'itinerary' => 'array',
        'inclusions' => 'array',
        'exclusions' => 'array',
        'available_dates' => 'array',
        'is_featured' => 'boolean',
        'is_active' => 'boolean',
        'duration_days' => 'integer',
        'duration_nights' => 'integer',
        'views' => 'integer',
    ];

    public function destination()
    {
        return $this->belongsTo(Destination::class);
    }

    public function images()
    {
        return $this->hasMany(PackageImage::class)->orderBy('sort_order');
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    /* ----------------- Scopes ----------------- */

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    /* ----------------- Accessors ----------------- */

    public function getMainImageUrlAttribute(): string
    {
        return media_url($this->main_image)
            ?? 'https://placehold.co/800x600?text=' . urlencode($this->title);
    }

    public function getEffectivePriceAttribute()
    {
        return $this->discount_price && $this->discount_price > 0
            ? $this->discount_price
            : $this->price;
    }

    public function getHasDiscountAttribute(): bool
    {
        return $this->discount_price !== null && $this->discount_price > 0 && $this->discount_price < $this->price;
    }

    public function getDiscountPercentAttribute(): int
    {
        if (! $this->has_discount || $this->price <= 0) {
            return 0;
        }

        return (int) round((($this->price - $this->discount_price) / $this->price) * 100);
    }

    public function getDurationLabelAttribute(): string
    {
        return trim("{$this->duration_days} Days / {$this->duration_nights} Nights");
    }
}
