<div class="max-w-xl rounded-2xl bg-white p-6 shadow-sm">
    <div class="space-y-4">
        <div>
            <label class="form-label">Name <span class="text-red-500">*</span></label>
            <input type="text" name="name" value="{{ old('name', $blogCategory->name ?? '') }}" required class="form-input-base">
        </div>
        <label class="flex items-center gap-2 text-sm">
            <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $blogCategory->is_active ?? true)) class="rounded border-slate-300 text-brand-600 focus:ring-brand-500">
            Active
        </label>
    </div>

    <div class="mt-6 flex items-center gap-3">
        <button type="submit" class="btn-primary">{{ $submitLabel ?? 'Save Category' }}</button>
        <a href="{{ route('admin.blog-categories.index') }}" class="btn-outline">Cancel</a>
    </div>
</div>
