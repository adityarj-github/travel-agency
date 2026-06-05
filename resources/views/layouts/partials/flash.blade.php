@if (session('success') || session('error') || session('status') || $errors->any())
    <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 6000)"
         class="fixed top-20 left-1/2 z-50 w-full max-w-md -translate-x-1/2 px-4">
        @if (session('success'))
            <div class="mb-2 flex items-start gap-3 rounded-lg border border-green-200 bg-green-50 p-4 text-sm text-green-800 shadow-lg">
                <span class="font-semibold">✓</span>
                <span class="flex-1">{{ session('success') }}</span>
                <button @click="show = false" class="text-green-600">&times;</button>
            </div>
        @endif
        @if (session('error'))
            <div class="mb-2 flex items-start gap-3 rounded-lg border border-red-200 bg-red-50 p-4 text-sm text-red-800 shadow-lg">
                <span class="font-semibold">!</span>
                <span class="flex-1">{{ session('error') }}</span>
                <button @click="show = false" class="text-red-600">&times;</button>
            </div>
        @endif
        @if (session('status'))
            <div class="mb-2 rounded-lg border border-blue-200 bg-blue-50 p-4 text-sm text-blue-800 shadow-lg">
                {{ session('status') }}
            </div>
        @endif
        @if ($errors->any())
            <div class="mb-2 rounded-lg border border-red-200 bg-red-50 p-4 text-sm text-red-800 shadow-lg">
                <p class="mb-1 font-semibold">Please fix the following:</p>
                <ul class="list-disc pl-5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
    </div>
@endif
