<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Concerns\HandlesImageUploads;
use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    use HandlesImageUploads;

    /**
     * Text/textarea settings that may be saved as-is.
     */
    private array $textKeys = [
        // general
        'site_name', 'footer_text', 'currency_symbol',
        'about_heading', 'about_content', 'mission', 'vision',
        'stat_travellers', 'stat_destinations', 'stat_years',
        // contact
        'phone', 'email', 'address', 'whatsapp', 'map_embed',
        // social
        'facebook', 'instagram', 'twitter', 'youtube',
        // seo
        'meta_title', 'meta_description',
    ];

    private array $imageKeys = ['logo', 'favicon', 'page_header'];

    public function edit()
    {
        $settings = Setting::all();

        return view('admin.settings.edit', compact('settings'));
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'site_name' => ['nullable', 'string', 'max:255'],
            'email' => ['nullable', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:50'],
            'whatsapp' => ['nullable', 'string', 'max:50'],
            'meta_title' => ['nullable', 'string', 'max:255'],
            'meta_description' => ['nullable', 'string', 'max:500'],
            'logo' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp,svg', 'max:2048'],
            'favicon' => ['nullable', 'image', 'mimes:png,ico,jpg,jpeg,webp', 'max:1024'],
            'page_header' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
        ]);

        // Save text settings.
        $pairs = [];
        foreach ($this->textKeys as $key) {
            $pairs[$key] = $request->input($key);
        }
        Setting::setMany($pairs);

        // Save image settings (only when a new file is uploaded).
        foreach ($this->imageKeys as $key) {
            if ($request->hasFile($key)) {
                $old = Setting::get($key);
                $path = $this->replaceImage($request->file($key), $old, 'settings');
                Setting::set($key, $path);
            }
        }

        Setting::flushCache();

        return back()->with('success', 'Website settings updated successfully.');
    }
}
