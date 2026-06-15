@extends('layouts.admin')

@section('title', 'Website Settings')
@section('page_title', 'Website Settings')
@section('breadcrumb', 'Manage global site configuration')

@section('content')
<form method="POST" action="{{ route('admin.settings.update') }}" enctype="multipart/form-data"
      x-data="{ tab: 'general' }">
    @csrf
    @method('PUT')

    <div class="mb-6 flex flex-wrap gap-2 border-b border-slate-200">
        @php $tabs = ['general' => 'General', 'contact' => 'Contact', 'social' => 'Social', 'reviews' => 'Reviews', 'seo' => 'SEO', 'branding' => 'Branding & About']; @endphp
        @foreach ($tabs as $key => $label)
            <button type="button" @click="tab = '{{ $key }}'"
                    class="-mb-px border-b-2 px-4 py-3 text-sm font-semibold transition"
                    :class="tab === '{{ $key }}' ? 'border-brand-600 text-brand-600' : 'border-transparent text-slate-500 hover:text-slate-800'">
                {{ $label }}
            </button>
        @endforeach
    </div>

    {{-- GENERAL --}}
    <div x-show="tab === 'general'" class="grid gap-6 lg:grid-cols-2">
        <div class="rounded-2xl bg-white p-6 shadow-sm">
            <h3 class="mb-4 font-bold text-slate-900">General</h3>
            <div class="space-y-4">
                <div>
                    <label class="form-label">Agency Name</label>
                    <input type="text" name="site_name" value="{{ $settings['site_name'] ?? config('app.name') }}" class="form-input-base">
                </div>
                <div>
                    <label class="form-label">Currency Symbol</label>
                    <input type="text" name="currency_symbol" value="{{ $settings['currency_symbol'] ?? '$' }}" class="form-input-base !w-24">
                </div>
                <div>
                    <label class="form-label">Footer Text</label>
                    <textarea name="footer_text" rows="3" class="form-input-base">{{ $settings['footer_text'] ?? '' }}</textarea>
                </div>
            </div>
        </div>
    </div>

    {{-- CONTACT --}}
    <div x-show="tab === 'contact'" x-cloak class="grid gap-6 lg:grid-cols-2">
        <div class="rounded-2xl bg-white p-6 shadow-sm">
            <h3 class="mb-4 font-bold text-slate-900">Contact Details</h3>
            <div class="space-y-4">
                <div><label class="form-label">Phone</label><input type="text" name="phone" value="{{ $settings['phone'] ?? '' }}" class="form-input-base"></div>
                <div><label class="form-label">Email</label><input type="email" name="email" value="{{ $settings['email'] ?? '' }}" class="form-input-base"></div>
                <div><label class="form-label">WhatsApp Number</label><input type="text" name="whatsapp" value="{{ $settings['whatsapp'] ?? '' }}" class="form-input-base"></div>
                <div><label class="form-label">Address</label><textarea name="address" rows="2" class="form-input-base">{{ $settings['address'] ?? '' }}</textarea></div>
            </div>
        </div>
        <div class="rounded-2xl bg-white p-6 shadow-sm">
            <h3 class="mb-4 font-bold text-slate-900">Google Map Embed</h3>
            <label class="form-label">Embed iframe code</label>
            <textarea name="map_embed" rows="6" class="form-input-base font-mono text-xs" placeholder="&lt;iframe src=...&gt;&lt;/iframe&gt;">{{ $settings['map_embed'] ?? '' }}</textarea>
            <p class="mt-1 text-xs text-slate-400">Paste the full &lt;iframe&gt; embed code from Google Maps.</p>
        </div>
    </div>

    {{-- SOCIAL --}}
    <div x-show="tab === 'social'" x-cloak class="grid gap-6 lg:grid-cols-2">
        <div class="rounded-2xl bg-white p-6 shadow-sm">
            <h3 class="mb-4 font-bold text-slate-900">Social Media Links</h3>
            <div class="space-y-4">
                <div><label class="form-label">Facebook URL</label><input type="url" name="facebook" value="{{ $settings['facebook'] ?? '' }}" class="form-input-base"></div>
                <div><label class="form-label">Instagram URL</label><input type="url" name="instagram" value="{{ $settings['instagram'] ?? '' }}" class="form-input-base"></div>
                <div><label class="form-label">Twitter / X URL</label><input type="url" name="twitter" value="{{ $settings['twitter'] ?? '' }}" class="form-input-base"></div>
                <div><label class="form-label">YouTube URL</label><input type="url" name="youtube" value="{{ $settings['youtube'] ?? '' }}" class="form-input-base"></div>
            </div>
        </div>
    </div>

    {{-- REVIEWS --}}
    <div x-show="tab === 'reviews'" x-cloak class="grid gap-6 lg:grid-cols-2">
        <div class="rounded-2xl bg-white p-6 shadow-sm">
            <h3 class="mb-1 font-bold text-slate-900">Google Reviews</h3>
            <p class="mb-4 text-xs text-slate-400">Shown as a rating badge in the "Loved by Our Guests" section on the home page.</p>
            <div class="space-y-4">
                <div><label class="form-label">Reviews URL</label><input type="url" name="google_reviews_url" value="{{ $settings['google_reviews_url'] ?? '' }}" class="form-input-base" placeholder="https://www.google.com/maps/place/..."></div>
                <div class="grid grid-cols-2 gap-3">
                    <div><label class="form-label">Rating</label><input type="text" name="google_rating" value="{{ $settings['google_rating'] ?? '' }}" class="form-input-base" placeholder="4.9"></div>
                    <div><label class="form-label">Review Count</label><input type="text" name="google_review_count" value="{{ $settings['google_review_count'] ?? '' }}" class="form-input-base" placeholder="120"></div>
                </div>
            </div>
        </div>
        <div class="rounded-2xl bg-white p-6 shadow-sm">
            <h3 class="mb-1 font-bold text-slate-900">Airbnb Reviews</h3>
            <p class="mb-4 text-xs text-slate-400">Shown as a rating badge alongside Google on the home page.</p>
            <div class="space-y-4">
                <div><label class="form-label">Reviews URL</label><input type="url" name="airbnb_reviews_url" value="{{ $settings['airbnb_reviews_url'] ?? '' }}" class="form-input-base" placeholder="https://www.airbnb.com/rooms/..."></div>
                <div class="grid grid-cols-2 gap-3">
                    <div><label class="form-label">Rating</label><input type="text" name="airbnb_rating" value="{{ $settings['airbnb_rating'] ?? '' }}" class="form-input-base" placeholder="4.95"></div>
                    <div><label class="form-label">Review Count</label><input type="text" name="airbnb_review_count" value="{{ $settings['airbnb_review_count'] ?? '' }}" class="form-input-base" placeholder="86"></div>
                </div>
            </div>
        </div>
    </div>

    {{-- SEO --}}
    <div x-show="tab === 'seo'" x-cloak class="grid gap-6 lg:grid-cols-2">
        <div class="rounded-2xl bg-white p-6 shadow-sm">
            <h3 class="mb-4 font-bold text-slate-900">Default SEO Meta</h3>
            <div class="space-y-4">
                <div><label class="form-label">Meta Title</label><input type="text" name="meta_title" value="{{ $settings['meta_title'] ?? '' }}" class="form-input-base"></div>
                <div><label class="form-label">Meta Description</label><textarea name="meta_description" rows="3" class="form-input-base">{{ $settings['meta_description'] ?? '' }}</textarea></div>
            </div>
        </div>
    </div>

    {{-- BRANDING & ABOUT --}}
    <div x-show="tab === 'branding'" x-cloak class="grid gap-6 lg:grid-cols-2">
        <div class="rounded-2xl bg-white p-6 shadow-sm">
            <h3 class="mb-4 font-bold text-slate-900">Branding Images</h3>
            <div class="space-y-5">
                <x-admin.image-input name="logo" label="Logo" :current="!empty($settings['logo']) ? asset('storage/'.$settings['logo']) : null" hint="PNG/SVG recommended." />
                <x-admin.image-input name="favicon" label="Favicon" :current="!empty($settings['favicon']) ? asset('storage/'.$settings['favicon']) : null" hint="Small square PNG/ICO." />
                <x-admin.image-input name="page_header" label="Default Page Header" :current="!empty($settings['page_header']) ? asset('storage/'.$settings['page_header']) : null" hint="Used behind inner page titles." />
            </div>
        </div>
        <div class="rounded-2xl bg-white p-6 shadow-sm">
            <h3 class="mb-4 font-bold text-slate-900">About Page Content</h3>
            <div class="space-y-4">
                <div><label class="form-label">About Heading</label><input type="text" name="about_heading" value="{{ $settings['about_heading'] ?? '' }}" class="form-input-base"></div>
                <div><label class="form-label">About Content</label><textarea name="about_content" rows="4" class="form-input-base">{{ $settings['about_content'] ?? '' }}</textarea></div>
                <div><label class="form-label">Mission</label><textarea name="mission" rows="2" class="form-input-base">{{ $settings['mission'] ?? '' }}</textarea></div>
                <div><label class="form-label">Vision</label><textarea name="vision" rows="2" class="form-input-base">{{ $settings['vision'] ?? '' }}</textarea></div>
                <div class="grid grid-cols-3 gap-3">
                    <div><label class="form-label">Travellers Stat</label><input type="text" name="stat_travellers" value="{{ $settings['stat_travellers'] ?? '' }}" class="form-input-base" placeholder="5000+"></div>
                    <div><label class="form-label">Destinations Stat</label><input type="text" name="stat_destinations" value="{{ $settings['stat_destinations'] ?? '' }}" class="form-input-base" placeholder="120+"></div>
                    <div><label class="form-label">Years Stat</label><input type="text" name="stat_years" value="{{ $settings['stat_years'] ?? '' }}" class="form-input-base" placeholder="10+"></div>
                </div>
            </div>
        </div>
    </div>

    <div class="mt-6">
        <button type="submit" class="btn-primary">Save Settings</button>
    </div>
</form>
@endsection
