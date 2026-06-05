<?php

namespace App\Models;

use App\Models\Concerns\HasSlug;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Destination extends Model
{
    use HasFactory, SoftDeletes, HasSlug;

    protected static string $slugSource = 'name';

    protected $fillable = [
        'name', 'slug', 'country', 'description', 'image',
        'is_active', 'is_featured', 'sort_order',
        'meta_title', 'meta_description',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'is_featured' => 'boolean',
        'sort_order' => 'integer',
    ];

    public function packages()
    {
        return $this->hasMany(Package::class);
    }

    public function activePackages()
    {
        return $this->hasMany(Package::class)->where('is_active', true);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function getImageUrlAttribute(): string
    {
        return media_url($this->image)
            ?? 'https://placehold.co/800x600?text=' . urlencode($this->name);
    }
}
