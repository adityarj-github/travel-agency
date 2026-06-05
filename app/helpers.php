<?php

use App\Models\Setting;
use Illuminate\Support\Str;

if (! function_exists('media_url')) {
    /**
     * Resolve a stored image path to a public URL. Pass-through for full URLs
     * (used by seeders/placeholders); otherwise resolve from the public disk.
     */
    function media_url(?string $path): ?string
    {
        if (blank($path)) {
            return null;
        }

        return Str::startsWith($path, ['http://', 'https://'])
            ? $path
            : asset('storage/' . $path);
    }
}

if (! function_exists('setting')) {
    /**
     * Retrieve a website setting value, with optional default.
     */
    function setting(string $key, $default = null)
    {
        return Setting::get($key, $default);
    }
}

if (! function_exists('setting_image')) {
    /**
     * Return a public URL for an image stored in a setting, or null.
     */
    function setting_image(string $key): ?string
    {
        return media_url(Setting::get($key));
    }
}
