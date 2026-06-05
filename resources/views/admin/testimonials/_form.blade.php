<div class="grid gap-6 lg:grid-cols-3">
    {{-- Main column --}}
    <div class="space-y-6 lg:col-span-2">
        <div class="rounded-2xl bg-white p-6 shadow-sm">
            <h3 class="mb-4 font-bold text-slate-900">Testimonial Details</h3>
            <div class="space-y-4">
                <div class="grid gap-4 sm:grid-cols-2">
                    <div>
                        <label class="form-label">Name <span class="text-red-500">*</span></label>
                        <input type="text" name="name" value="{{ old('name', $testimonial->name ?? '') }}" required class="form-input-base" placeholder="e.g. Jane Doe">
                    </div>
                    <div>
                        <label class="form-label">Location</label>
                        <input type="text" name="location" value="{{ old('location', $testimonial->location ?? '') }}" class="form-input-base" placeholder="e.g. London, UK">
                    </div>
                </div>
                <div>
                    <label class="form-label">Rating <span class="text-red-500">*</span></label>
                    <select name="rating" required class="form-input-base">
                        @for ($i = 1; $i <= 5; $i++)
                            <option value="{{ $i }}" @selected(old('rating', $testimonial->rating ?? 5) == $i)>{{ $i }} star{{ $i > 1 ? 's' : '' }}</option>
                        @endfor
                    </select>
                </div>
                <div>
                    <label class="form-label">Message <span class="text-red-500">*</span></label>
                    <textarea name="message" rows="5" maxlength="2000" required class="form-input-base" placeholder="What the customer said...">{{ old('message', $testimonial->message ?? '') }}</textarea>
                    <p class="mt-1 text-xs text-slate-400">Maximum 2000 characters.</p>
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
                    <input type="number" name="sort_order" value="{{ old('sort_order', $testimonial->sort_order ?? 0) }}" class="form-input-base">
                    <p class="mt-1 text-xs text-slate-400">Lower numbers appear first.</p>
                </div>
                <label class="flex items-center gap-2 text-sm">
                    <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $testimonial->is_active ?? true)) class="rounded border-slate-300 text-brand-600 focus:ring-brand-500">
                    Active (visible on site)
                </label>
            </div>
        </div>

        <div class="rounded-2xl bg-white p-6 shadow-sm">
            <h3 class="mb-4 font-bold text-slate-900">Avatar</h3>
            <x-admin.image-input name="image" label="" :current="isset($testimonial) ? $testimonial->image_url : null" :required="false" />
        </div>
    </div>
</div>

<div class="mt-6 flex items-center gap-3">
    <button type="submit" class="btn-primary">{{ $submitLabel ?? 'Save Testimonial' }}</button>
    <a href="{{ route('admin.testimonials.index') }}" class="btn-outline">Cancel</a>
</div>
