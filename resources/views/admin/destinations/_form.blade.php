<div class="grid gap-6 lg:grid-cols-3">
    {{-- Main column --}}
    <div class="space-y-6 lg:col-span-2">
        <div class="rounded-2xl bg-white p-6 shadow-sm">
            <h3 class="mb-4 font-bold text-slate-900">Basic Information</h3>
            <div class="space-y-4">
                <div>
                    <label class="form-label">Name <span class="text-red-500">*</span></label>
                    <input type="text" name="name" value="{{ old('name', $destination->name ?? '') }}" required class="form-input-base">
                </div>
                <div>
                    <label class="form-label">Country</label>
                    <input type="text" name="country" value="{{ old('country', $destination->country ?? '') }}" class="form-input-base" placeholder="e.g. Indonesia">
                </div>
                <div>
                    <label class="form-label">Description</label>
                    <textarea name="description" rows="6" class="form-input-base">{{ old('description', $destination->description ?? '') }}</textarea>
                </div>
            </div>
        </div>

        {{-- SEO --}}
        <div class="rounded-2xl bg-white p-6 shadow-sm">
            <h3 class="mb-4 font-bold text-slate-900">SEO Meta</h3>
            <div class="space-y-4">
                <div>
                    <label class="form-label">Meta Title</label>
                    <input type="text" name="meta_title" value="{{ old('meta_title', $destination->meta_title ?? '') }}" class="form-input-base">
                </div>
                <div>
                    <label class="form-label">Meta Description</label>
                    <textarea name="meta_description" rows="2" class="form-input-base">{{ old('meta_description', $destination->meta_description ?? '') }}</textarea>
                </div>
            </div>
        </div>
    </div>

    {{-- Sidebar --}}
    <div class="space-y-6">
        <div class="rounded-2xl bg-white p-6 shadow-sm">
            <h3 class="mb-4 font-bold text-slate-900">Image</h3>
            <x-admin.image-input name="image" label="" :current="isset($destination) ? $destination->image_url : null" :required="false" :multiple="false" hint="JPG, PNG or WEBP. Max 4MB." />
        </div>

        <div class="rounded-2xl bg-white p-6 shadow-sm">
            <h3 class="mb-4 font-bold text-slate-900">Status</h3>
            <label class="mb-3 flex items-center gap-2 text-sm">
                <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $destination->is_active ?? true)) class="rounded border-slate-300 text-brand-600 focus:ring-brand-500">
                Active (visible on site)
            </label>
            <label class="flex items-center gap-2 text-sm">
                <input type="checkbox" name="is_featured" value="1" @checked(old('is_featured', $destination->is_featured ?? false)) class="rounded border-slate-300 text-brand-600 focus:ring-brand-500">
                Featured destination
            </label>
        </div>

        <div class="rounded-2xl bg-white p-6 shadow-sm">
            <h3 class="mb-4 font-bold text-slate-900">Ordering</h3>
            <div>
                <label class="form-label">Sort Order</label>
                <input type="number" name="sort_order" value="{{ old('sort_order', $destination->sort_order ?? 0) }}" class="form-input-base">
                <p class="mt-1 text-xs text-slate-400">Lower numbers appear first.</p>
            </div>
        </div>
    </div>
</div>

<div class="mt-6 flex items-center gap-3">
    <button type="submit" class="btn-primary">{{ $submitLabel ?? 'Save Destination' }}</button>
    <a href="{{ route('admin.destinations.index') }}" class="btn-outline">Cancel</a>
</div>
