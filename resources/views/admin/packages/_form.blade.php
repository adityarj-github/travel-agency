@php
    $itineraryOld = old('itinerary', isset($package) ? ($package->itinerary ?? []) : []);
    if (empty($itineraryOld)) { $itineraryOld = [['day' => '1', 'title' => '', 'detail' => '']]; }
    $inclusionsText = old('inclusions', isset($package) ? implode("\n", $package->inclusions ?? []) : '');
    $exclusionsText = old('exclusions', isset($package) ? implode("\n", $package->exclusions ?? []) : '');
    $datesText = old('available_dates', isset($package) ? implode("\n", $package->available_dates ?? []) : '');
@endphp

<div class="grid gap-6 lg:grid-cols-3">
    {{-- Main column --}}
    <div class="space-y-6 lg:col-span-2">
        <div class="rounded-2xl bg-white p-6 shadow-sm">
            <h3 class="mb-4 font-bold text-slate-900">Basic Information</h3>
            <div class="space-y-4">
                <div>
                    <label class="form-label">Title <span class="text-red-500">*</span></label>
                    <input type="text" name="title" value="{{ old('title', $package->title ?? '') }}" required class="form-input-base">
                </div>
                <div class="grid gap-4 sm:grid-cols-2">
                    <div>
                        <label class="form-label">Destination</label>
                        <select name="destination_id" class="form-input-base">
                            <option value="">— Select —</option>
                            @foreach ($destinations as $d)
                                <option value="{{ $d->id }}" @selected(old('destination_id', $package->destination_id ?? '') == $d->id)>{{ $d->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="form-label">Location (display)</label>
                        <input type="text" name="location" value="{{ old('location', $package->location ?? '') }}" class="form-input-base" placeholder="e.g. Bali, Indonesia">
                    </div>
                </div>
                <div class="grid gap-4 sm:grid-cols-3">
                    <div>
                        <label class="form-label">Category</label>
                        <input type="text" name="category" value="{{ old('category', $package->category ?? '') }}" class="form-input-base" placeholder="Adventure" list="cat-list">
                        <datalist id="cat-list"><option>Adventure</option><option>Honeymoon</option><option>Family</option><option>Luxury</option><option>Beach</option><option>Cultural</option></datalist>
                    </div>
                    <div>
                        <label class="form-label">Package Type</label>
                        <input type="text" name="package_type" value="{{ old('package_type', $package->package_type ?? '') }}" class="form-input-base" placeholder="International" list="type-list">
                        <datalist id="type-list"><option>Domestic</option><option>International</option></datalist>
                    </div>
                    <div>
                        <label class="form-label">Tour Type</label>
                        <input type="text" name="tour_type" value="{{ old('tour_type', $package->tour_type ?? '') }}" class="form-input-base" placeholder="Group" list="tour-list">
                        <datalist id="tour-list"><option>Group</option><option>Private</option><option>Solo</option></datalist>
                    </div>
                </div>
                <div>
                    <label class="form-label">Short Description</label>
                    <textarea name="short_description" rows="2" class="form-input-base" placeholder="Brief summary shown on cards...">{{ old('short_description', $package->short_description ?? '') }}</textarea>
                </div>
                <div>
                    <label class="form-label">Full Description</label>
                    <textarea name="description" rows="6" class="form-input-base rich-text">{{ old('description', $package->description ?? '') }}</textarea>
                </div>
            </div>
        </div>

        {{-- Itinerary --}}
        <div class="rounded-2xl bg-white p-6 shadow-sm" x-data="{ items: @js(array_values($itineraryOld)) }">
            <div class="mb-4 flex items-center justify-between">
                <h3 class="font-bold text-slate-900">Day-wise Itinerary</h3>
                <button type="button" @click="items.push({day: String(items.length+1), title: '', detail: ''})" class="text-sm font-medium text-brand-600">+ Add Day</button>
            </div>
            <div class="space-y-3">
                <template x-for="(item, i) in items" :key="i">
                    <div class="rounded-lg border border-slate-200 p-3">
                        <div class="mb-2 flex items-center gap-2">
                            <input type="text" :name="`itinerary[${i}][day]`" x-model="item.day" class="form-input-base !w-20 !py-1.5" placeholder="Day">
                            <input type="text" :name="`itinerary[${i}][title]`" x-model="item.title" class="form-input-base !py-1.5" placeholder="Title (e.g. Arrival & City Tour)">
                            <button type="button" @click="items.splice(i,1)" class="shrink-0 text-red-500 hover:text-red-700">&times;</button>
                        </div>
                        <textarea :name="`itinerary[${i}][detail]`" x-model="item.detail" rows="2" class="form-input-base !py-1.5" placeholder="Details for this day..."></textarea>
                    </div>
                </template>
            </div>
        </div>

        {{-- Inclusions / Exclusions --}}
        <div class="rounded-2xl bg-white p-6 shadow-sm">
            <h3 class="mb-4 font-bold text-slate-900">Inclusions &amp; Exclusions</h3>
            <div class="grid gap-4 sm:grid-cols-2">
                <div>
                    <label class="form-label">Inclusions <span class="text-xs text-slate-400">(one per line)</span></label>
                    <textarea name="inclusions" rows="5" class="form-input-base" placeholder="Accommodation&#10;Daily breakfast&#10;Airport transfers">{{ $inclusionsText }}</textarea>
                </div>
                <div>
                    <label class="form-label">Exclusions <span class="text-xs text-slate-400">(one per line)</span></label>
                    <textarea name="exclusions" rows="5" class="form-input-base" placeholder="International flights&#10;Travel insurance&#10;Personal expenses">{{ $exclusionsText }}</textarea>
                </div>
            </div>
        </div>

        {{-- Terms --}}
        <div class="rounded-2xl bg-white p-6 shadow-sm">
            <h3 class="mb-4 font-bold text-slate-900">Terms &amp; Conditions</h3>
            <textarea name="terms" rows="4" class="form-input-base" placeholder="Booking and cancellation terms...">{{ old('terms', $package->terms ?? '') }}</textarea>
        </div>

        {{-- SEO --}}
        <div class="rounded-2xl bg-white p-6 shadow-sm">
            <h3 class="mb-4 font-bold text-slate-900">SEO Meta</h3>
            <div class="space-y-4">
                <div>
                    <label class="form-label">Meta Title</label>
                    <input type="text" name="meta_title" value="{{ old('meta_title', $package->meta_title ?? '') }}" class="form-input-base">
                </div>
                <div>
                    <label class="form-label">Meta Description</label>
                    <textarea name="meta_description" rows="2" class="form-input-base">{{ old('meta_description', $package->meta_description ?? '') }}</textarea>
                </div>
            </div>
        </div>
    </div>

    {{-- Sidebar --}}
    <div class="space-y-6">
        <div class="rounded-2xl bg-white p-6 shadow-sm">
            <h3 class="mb-4 font-bold text-slate-900">Pricing &amp; Duration</h3>
            <div class="space-y-4">
                <div>
                    <label class="form-label">Price <span class="text-red-500">*</span></label>
                    <input type="number" step="0.01" name="price" value="{{ old('price', $package->price ?? '') }}" required class="form-input-base">
                </div>
                <div>
                    <label class="form-label">Discount Price</label>
                    <input type="number" step="0.01" name="discount_price" value="{{ old('discount_price', $package->discount_price ?? '') }}" class="form-input-base">
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="form-label">Days <span class="text-red-500">*</span></label>
                        <input type="number" name="duration_days" value="{{ old('duration_days', $package->duration_days ?? 1) }}" required class="form-input-base">
                    </div>
                    <div>
                        <label class="form-label">Nights</label>
                        <input type="number" name="duration_nights" value="{{ old('duration_nights', $package->duration_nights ?? 0) }}" class="form-input-base">
                    </div>
                </div>
                <div>
                    <label class="form-label">Max People</label>
                    <input type="number" name="max_people" value="{{ old('max_people', $package->max_people ?? '') }}" class="form-input-base">
                </div>
            </div>
        </div>

        <div class="rounded-2xl bg-white p-6 shadow-sm">
            <h3 class="mb-4 font-bold text-slate-900">Status</h3>
            <label class="mb-3 flex items-center gap-2 text-sm">
                <input type="checkbox" name="is_featured" value="1" @checked(old('is_featured', $package->is_featured ?? false)) class="rounded border-slate-300 text-brand-600 focus:ring-brand-500">
                Featured package
            </label>
            <label class="flex items-center gap-2 text-sm">
                <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $package->is_active ?? true)) class="rounded border-slate-300 text-brand-600 focus:ring-brand-500">
                Active (visible on site)
            </label>
        </div>

        <div class="rounded-2xl bg-white p-6 shadow-sm">
            <h3 class="mb-4 font-bold text-slate-900">Main Image</h3>
            <x-admin.image-input name="main_image" label="" :current="isset($package) ? $package->main_image_url : null" :required="!isset($package)" />
        </div>

        <div class="rounded-2xl bg-white p-6 shadow-sm">
            <h3 class="mb-4 font-bold text-slate-900">Gallery Images</h3>
            <x-admin.image-input name="gallery_images" label="" :multiple="true" hint="Select multiple images." />

            @if (isset($package) && $package->images->isNotEmpty())
                <div class="mt-4 grid grid-cols-3 gap-2">
                    @foreach ($package->images as $img)
                        <div class="relative">
                            <img src="{{ $img->url }}" class="h-20 w-full rounded-lg object-cover">
                            <button type="button"
                                    onclick="if(confirm('Remove this image?')){document.getElementById('del-img-{{ $img->id }}').submit();}"
                                    class="absolute right-1 top-1 flex h-6 w-6 items-center justify-center rounded-full bg-red-500 text-white">&times;</button>
                        </div>
                    @endforeach
                </div>
                @foreach ($package->images as $img)
                    <form id="del-img-{{ $img->id }}" method="POST" action="{{ route('admin.packages.images.destroy', $img) }}" class="hidden">
                        @csrf @method('DELETE')
                    </form>
                @endforeach
            @endif
        </div>

        <div class="rounded-2xl bg-white p-6 shadow-sm">
            <h3 class="mb-4 font-bold text-slate-900">Available Dates</h3>
            <textarea name="available_dates" rows="3" class="form-input-base" placeholder="2026-07-01&#10;2026-08-15">{{ $datesText }}</textarea>
            <p class="mt-1 text-xs text-slate-400">One date (YYYY-MM-DD) per line.</p>
        </div>
    </div>
</div>

<div class="mt-6 flex items-center gap-3">
    <button type="submit" class="btn-primary">{{ $submitLabel ?? 'Save Package' }}</button>
    <a href="{{ route('admin.packages.index') }}" class="btn-outline">Cancel</a>
</div>
