@if (session('success'))
    <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)"
         class="mb-4 flex items-start justify-between gap-3 rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-800">
        <span>{{ session('success') }}</span>
        <button @click="show = false" class="text-green-500">&times;</button>
    </div>
@endif
@if (session('error'))
    <div x-data="{ show: true }" x-show="show"
         class="mb-4 flex items-start justify-between gap-3 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800">
        <span>{{ session('error') }}</span>
        <button @click="show = false" class="text-red-500">&times;</button>
    </div>
@endif
@if ($errors->any())
    <div class="mb-4 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800">
        <p class="mb-1 font-semibold">Please correct the following {{ $errors->count() }} error(s):</p>
        <ul class="list-disc pl-5">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
