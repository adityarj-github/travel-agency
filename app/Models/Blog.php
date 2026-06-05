<?php

namespace App\Models;

use App\Models\Concerns\HasSlug;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Blog extends Model
{
    use HasFactory, SoftDeletes, HasSlug;

    protected static string $slugSource = 'title';

    protected $fillable = [
        'blog_category_id', 'title', 'slug', 'author', 'featured_image',
        'excerpt', 'content', 'meta_title', 'meta_description',
        'is_published', 'published_at', 'views',
    ];

    protected $casts = [
        'is_published' => 'boolean',
        'published_at' => 'datetime',
        'views' => 'integer',
    ];

    public function category()
    {
        return $this->belongsTo(BlogCategory::class, 'blog_category_id');
    }

    public function scopePublished($query)
    {
        return $query->where('is_published', true)
            ->where(function ($q) {
                $q->whereNull('published_at')->orWhere('published_at', '<=', now());
            });
    }

    public function getFeaturedImageUrlAttribute(): string
    {
        return media_url($this->featured_image)
            ?? 'https://placehold.co/1200x675?text=' . urlencode($this->title);
    }
}
