<header class="sticky top-0 z-20 flex h-16 items-center justify-between border-b border-slate-200 bg-white px-4 sm:px-6">
    <button @click="sidebarOpen = !sidebarOpen" class="text-slate-500 lg:hidden" aria-label="Toggle sidebar">
        <svg class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"/></svg>
    </button>

    <div class="hidden text-sm text-slate-400 lg:block">
        {{ now()->format('l, F j, Y') }}
    </div>

    <div class="flex items-center gap-4" x-data="{ menu: false }">
        @can('manage_inquiries')
        <a href="{{ route('admin.inquiries.index') }}" class="relative text-slate-500 hover:text-brand-600" title="Contact inquiries">
            <svg class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
            @php $unread = \App\Models\ContactInquiry::where('is_read', false)->count(); @endphp
            @if ($unread)
                <span class="absolute -right-1 -top-1 flex h-4 min-w-4 items-center justify-center rounded-full bg-red-500 px-1 text-[10px] font-semibold text-white">{{ $unread }}</span>
            @endif
        </a>
        @endcan

        <div class="relative">
            <button @click="menu = !menu" class="flex items-center gap-2">
                <span class="flex h-9 w-9 items-center justify-center rounded-full bg-brand-100 font-semibold text-brand-700">
                    {{ strtoupper(substr(auth()->user()->name ?? 'A', 0, 1)) }}
                </span>
                <span class="hidden text-sm font-medium text-slate-700 sm:block">{{ auth()->user()->name ?? 'Admin' }}</span>
                <svg class="hidden h-4 w-4 text-slate-400 sm:block" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
            </button>
            <div x-show="menu" x-cloak @click.outside="menu = false"
                 class="absolute right-0 mt-2 w-48 rounded-lg border border-slate-100 bg-white py-1 shadow-lg">
                <span class="block px-4 py-2 text-xs text-slate-400">{{ auth()->user()->role_label }}</span>
                @can('manage_settings')
                    <a href="{{ route('admin.settings.edit') }}" class="block px-4 py-2 text-sm text-slate-600 hover:bg-slate-50">Settings</a>
                @endcan
                <form method="POST" action="{{ route('admin.logout') }}">
                    @csrf
                    <button type="submit" class="block w-full px-4 py-2 text-left text-sm text-red-600 hover:bg-red-50">Logout</button>
                </form>
            </div>
        </div>
    </div>
</header>
