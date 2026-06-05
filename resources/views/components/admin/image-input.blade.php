@props(['name', 'label' => 'Image', 'current' => null, 'required' => false, 'multiple' => false, 'hint' => 'JPG, PNG or WEBP. Max 4MB.'])

<div x-data="{ preview: @js($current), files: [] }">
    <label class="form-label">{{ $label }} @if($required)<span class="text-red-500">*</span>@endif</label>

    <div class="flex items-start gap-4">
        @unless ($multiple)
            <div class="h-24 w-24 shrink-0 overflow-hidden rounded-lg border border-slate-200 bg-slate-50">
                <template x-if="preview">
                    <img :src="preview" class="h-full w-full object-cover">
                </template>
                <template x-if="!preview">
                    <div class="flex h-full w-full items-center justify-center text-xs text-slate-300">No image</div>
                </template>
            </div>
        @endunless

        <div class="flex-1">
            <input type="file" name="{{ $multiple ? $name.'[]' : $name }}" {{ $multiple ? 'multiple' : '' }} {{ $required ? 'required' : '' }}
                   accept="image/jpeg,image/png,image/webp"
                   @change="
                        @if ($multiple)
                            files = Array.from($event.target.files).map(f => URL.createObjectURL(f))
                        @else
                            preview = $event.target.files[0] ? URL.createObjectURL($event.target.files[0]) : preview
                        @endif
                   "
                   class="block w-full text-sm text-slate-500 file:mr-4 file:rounded-lg file:border-0 file:bg-brand-50 file:px-4 file:py-2 file:text-sm file:font-semibold file:text-brand-700 hover:file:bg-brand-100">
            <p class="mt-1 text-xs text-slate-400">{{ $hint }}</p>

            @if ($multiple)
                <div class="mt-3 flex flex-wrap gap-2">
                    <template x-for="(src, i) in files" :key="i">
                        <img :src="src" class="h-16 w-16 rounded-lg object-cover">
                    </template>
                </div>
            @endif
        </div>
    </div>
    @error($name)<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
    @error($name.'.*')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
</div>
