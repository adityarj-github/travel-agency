<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PackageImage extends Model
{
    use HasFactory;

    protected $fillable = ['package_id', 'image_path', 'sort_order'];

    public function package()
    {
        return $this->belongsTo(Package::class);
    }

    public function getUrlAttribute(): string
    {
        return media_url($this->image_path);
    }
}
