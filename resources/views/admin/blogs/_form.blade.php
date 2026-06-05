<div class="grid gap-6 lg:grid-cols-3">
    {{-- Main column --}}
    <div class="space-y-6 lg:col-span-2">
        <div class="rounded-2xl bg-white p-6 shadow-sm">
            <h3 class="mb-4 font-bold text-slate-900">Post Content</h3>
            <div class="space-y-4">
                <div>
                    <label class="form-label">Title <span class="text-red-500">*</span></label>
                    <input type="text" name="title" value="{{ old('title', $blog->title ?? '') }}" required class="form-input-base">
                </div>
                <div>
                    <label class="form-label">Excerpt <span class="text-xs text-slate-400">(max 500 chars)</span></label>
                    <textarea name="excerpt" rows="3" maxlength="500" class="form-input-base" placeholder="Short summary shown in listings...">{{ old('excerpt', $blog->excerpt ?? '') }}</textarea>
                </div>
                <div>
                    <label class="form-label">Content <span class="text-red-500">*</span></label>
                    <textarea name="content" rows="10" required class="form-input-base rich-text">{{ old('content', $blog->content ?? '') }}</textarea>
                </div>
            </div>
        </div>

        {{-- SEO --}}
        <div class="rounded-2xl bg-white p-6 shadow-sm">
            <h3 class="mb-4 font-bold text-slate-900">SEO Meta</h3>
            <div class="space-y-4">
                <div>
                    <label class="form-label">Meta Title</label>
                    <input type="text" name="meta_title" value="{{ old('meta_title', $blog->meta_title ?? '') }}" class="form-input-base">
                </div>
                <div>
                    <label class="form-label">Meta Description</label>
                    <textarea name="meta_description" rows="2" class="form-input-base">{{ old('meta_description', $blog->meta_description ?? '') }}</textarea>
                </div>
            </div>
        </div>
    </div>

    {{-- Sidebar --}}
    <div class="space-y-6">
        <div class="rounded-2xl bg-white p-6 shadow-sm">
            <h3 class="mb-4 font-bold text-slate-900">Details</h3>
            <div class="space-y-4">
                <div>
                    <label class="form-label">Category</label>
                    <select name="blog_category_id" class="form-input-base">
                        <option value="">— Select —</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}" @selected(old('blog_category_id', $blog->blog_category_id ?? '') == $category->id)>{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="form-label">Author</label>
                    <input type="text" name="author" value="{{ old('author', $blog->author ?? '') }}" class="form-input-base" placeholder="Author name">
                </div>
            </div>
        </div>

        <div class="rounded-2xl bg-white p-6 shadow-sm">
            <h3 class="mb-4 font-bold text-slate-900">Featured Image</h3>
            <x-admin.image-input name="featured_image" label="" :current="$blog->featured_image_url ?? null" :required="false" />
        </div>

        <div class="rounded-2xl bg-white p-6 shadow-sm">
            <h3 class="mb-4 font-bold text-slate-900">Publishing</h3>
            <label class="mb-4 flex items-center gap-2 text-sm">
                <input type="checkbox" name="is_published" value="1" @checked(old('is_published', $blog->is_published ?? false)) class="rounded border-slate-300 text-brand-600 focus:ring-brand-500">
                Published
            </label>
            <div>
                <label class="form-label">Publish Date</label>
                <input type="datetime-local" name="published_at" value="{{ old('published_at', isset($blog) && $blog->published_at ? $blog->published_at->format('Y-m-d\TH:i') : '') }}" class="form-input-base">
            </div>
        </div>
    </div>
</div>

<div class="mt-6 flex items-center gap-3">
    <button type="submit" class="btn-primary">{{ $submitLabel ?? 'Save Post' }}</button>
    <a href="{{ route('admin.blogs.index') }}" class="btn-outline">Cancel</a>
</div>
