<?php

namespace App\Models\Concerns;

use Illuminate\Support\Str;

/**
 * Auto-generates a unique slug from a source attribute.
 * Models using this trait may define:
 *   protected static string $slugSource = 'title';  // defaults to 'name'
 */
trait HasSlug
{
    public static function bootHasSlug(): void
    {
        static::saving(function ($model) {
            $source = property_exists($model, 'slugSource') ? $model::$slugSource : 'name';

            // Only (re)generate when slug is empty.
            if (empty($model->slug) && ! empty($model->{$source})) {
                $model->slug = $model->generateUniqueSlug($model->{$source});
            }
        });
    }

    public function generateUniqueSlug(string $value): string
    {
        $base = Str::slug($value);
        $slug = $base;
        $i = 2;

        while (
            static::withoutGlobalScopes()
                ->where('slug', $slug)
                ->where($this->getKeyName(), '!=', $this->getKey())
                ->exists()
        ) {
            $slug = $base . '-' . $i++;
        }

        return $slug;
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }
}
