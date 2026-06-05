<div class="grid gap-6 lg:grid-cols-3">
    {{-- Main column --}}
    <div class="space-y-6 lg:col-span-2">
        <div class="rounded-2xl bg-white p-6 shadow-sm">
            <h3 class="mb-4 font-bold text-slate-900">Slider Content</h3>
            <div class="space-y-4">
                <div>
                    <label class="form-label">Title <span class="text-red-500">*</span></label>
                    <input type="text" name="title" value="{{ old('title', $slider->title ?? '') }}" required class="form-input-base" placeholder="e.g. Discover Your Next Adventure">
                </div>
                <div>
                    <label class="form-label">Subtitle</label>
                    <input type="text" name="subtitle" value="{{ old('subtitle', $slider->subtitle ?? '') }}" class="form-input-base" placeholder="e.g. Handpicked destinations at unbeatable prices">
                </div>
                <div class="grid gap-4 sm:grid-cols-2">
                    <div>
                        <label class="form-label">Button Text</label>
                        <input type="text" name="button_text" value="{{ old('button_text', $slider->button_text ?? '') }}" class="form-input-base" placeholder="e.g. Explore Packages">
                    </div>
                    <div>
                        <label class="form-label">Button Link</label>
                        <input type="url" name="button_link" value="{{ old('button_link', $slider->button_link ?? '') }}" class="form-input-base" placeholder="https://example.com/packages">
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Sidebar --}}
    <div class="space-y-6">
        <div class="rounded-2xl bg-white p-6 shadow-sm">
            <h3 class="mb-4 font-bold text-slate-900">Settings</h3>
            <div class="space-y-4">
                <div>
                    <label class="form-label">Sort Order</label>
                    <input type="number" name="sort_order" value="{{ old('sort_order', $slider->sort_order ?? 0) }}" class="form-input-base">
                    <p class="mt-1 text-xs text-slate-400">Lower numbers appear first.</p>
                </div>
                <label class="flex items-center gap-2 text-sm">
                    <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $slider->is_active ?? true)) class="rounded border-slate-300 text-brand-600 focus:ring-brand-500">
                    Active (visible on site)
                </label>
            </div>
        </div>

        <div class="rounded-2xl bg-white p-6 shadow-sm">
            <h3 class="mb-4 font-bold text-slate-900">Image</h3>
            <x-admin.image-input name="image" label="" :current="isset($slider) ? $slider->image_url : null" :required="!isset($slider)" />
        </div>
    </div>
</div>

<div class="mt-6 flex items-center gap-3">
    <button type="submit" class="btn-primary">{{ $submitLabel ?? 'Save Slider' }}</button>
    <a href="{{ route('admin.sliders.index') }}" class="btn-outline">Cancel</a>
</div>
