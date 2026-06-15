@php
    // 'permission' => null means visible to any staff member.
    // Items are grouped into labelled sections; a section is hidden entirely
    // when the user can see none of its items.
    $groups = [
        [
            'heading' => null,
            'items' => [
                ['route' => 'admin.dashboard', 'pattern' => 'admin.dashboard', 'label' => 'Dashboard', 'permission' => null, 'icon' => 'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6'],
            ],
        ],
        [
            'heading' => 'Sales',
            'items' => [
                ['route' => 'admin.bookings.index', 'pattern' => 'admin.bookings.*', 'label' => 'Bookings', 'permission' => 'manage_bookings', 'icon' => 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4'],
                ['route' => 'admin.payment-links.index', 'pattern' => 'admin.payment-links.*', 'label' => 'Payment Links', 'permission' => 'manage_bookings', 'icon' => 'M13.828 10.172a4 4 0 010 5.656l-3 3a4 4 0 01-5.656-5.656l1.5-1.5M10.172 13.828a4 4 0 010-5.656l3-3a4 4 0 015.656 5.656l-1.5 1.5'],
                ['route' => 'admin.coupons.index', 'pattern' => 'admin.coupons.*', 'label' => 'Coupons', 'permission' => 'manage_coupons', 'icon' => 'M7 7h.01M7 3h5a1.99 1.99 0 011.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.99 1.99 0 013 12V7a4 4 0 014-4z'],
                ['route' => 'admin.inquiries.index', 'pattern' => 'admin.inquiries.*', 'label' => 'Contact Inquiries', 'permission' => 'manage_inquiries', 'icon' => 'M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z', 'badge' => 'inquiries'],
            ],
        ],
        [
            'heading' => 'Catalog',
            'items' => [
                ['route' => 'admin.packages.index', 'pattern' => 'admin.packages.*', 'label' => 'Packages', 'permission' => 'manage_content', 'icon' => 'M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z'],
                ['route' => 'admin.destinations.index', 'pattern' => 'admin.destinations.*', 'label' => 'Destinations', 'permission' => 'manage_content', 'icon' => 'M17.657 16.657L13.414 20.9a2 2 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z M15 11a3 3 0 11-6 0 3 3 0 016 0z'],
            ],
        ],
        [
            'heading' => 'Content',
            'items' => [
                ['route' => 'admin.blogs.index', 'pattern' => 'admin.blogs.*', 'label' => 'Blogs', 'permission' => 'manage_content', 'icon' => 'M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z'],
                ['route' => 'admin.blog-categories.index', 'pattern' => 'admin.blog-categories.*', 'label' => 'Blog Categories', 'permission' => 'manage_content', 'icon' => 'M7 7h.01M7 3h5a1.99 1.99 0 011.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.99 1.99 0 013 12V7a4 4 0 014-4z'],
                ['route' => 'admin.sliders.index', 'pattern' => 'admin.sliders.*', 'label' => 'Sliders / Banners', 'permission' => 'manage_content', 'icon' => 'M4 5a1 1 0 011-1h14a1 1 0 011 1v14a1 1 0 01-1 1H5a1 1 0 01-1-1V5z M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14'],
                ['route' => 'admin.galleries.index', 'pattern' => 'admin.galleries.*', 'label' => 'Gallery', 'permission' => 'manage_content', 'icon' => 'M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z'],
                ['route' => 'admin.testimonials.index', 'pattern' => 'admin.testimonials.*', 'label' => 'Testimonials', 'permission' => 'manage_content', 'icon' => 'M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z'],
            ],
        ],
        [
            'heading' => 'System',
            'items' => [
                ['route' => 'admin.users.index', 'pattern' => 'admin.users.*', 'label' => 'Staff & Roles', 'permission' => 'manage_users', 'icon' => 'M17 20h5v-2a4 4 0 00-3-3.87M9 20H4v-2a4 4 0 013-3.87m6-1.13a4 4 0 10-4-4 4 4 0 004 4zm6 0a4 4 0 10-3-6.65'],
                ['route' => 'admin.settings.edit', 'pattern' => 'admin.settings.*', 'label' => 'Website Settings', 'permission' => 'manage_settings', 'icon' => 'M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z M15 12a3 3 0 11-6 0 3 3 0 016 0z'],
            ],
        ],
    ];

    $user = auth()->user();
    $unreadInquiries = $user?->can('manage_inquiries')
        ? \App\Models\ContactInquiry::where('is_read', false)->count()
        : 0;
@endphp

<aside class="fixed inset-y-0 left-0 z-40 flex w-64 transform flex-col bg-slate-900 text-slate-300 shadow-xl transition-transform duration-200 lg:translate-x-0"
       :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'">

    {{-- Brand --}}
    <div class="flex h-16 shrink-0 items-center gap-3 border-b border-white/5 px-5">
        <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 overflow-hidden">
            <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-gradient-to-br from-brand-400 to-brand-600 text-lg text-white shadow-lg shadow-brand-900/40">✈</span>
            <span class="flex flex-col overflow-hidden">
                <span class="truncate text-sm font-bold leading-tight text-white">{{ setting('site_name', config('app.name')) }}</span>
                <span class="truncate text-[11px] font-medium leading-tight text-slate-500">Admin Panel</span>
            </span>
        </a>
    </div>

    {{-- Navigation --}}
    <nav class="no-scrollbar flex-1 space-y-6 overflow-y-auto px-3 py-5">
        @foreach ($groups as $group)
            @php
                $visible = collect($group['items'])->filter(fn ($i) => ! $i['permission'] || $user?->can($i['permission']));
            @endphp
            @continue($visible->isEmpty())

            <div class="space-y-1">
                @if ($group['heading'])
                    <p class="px-3 pb-1 text-[11px] font-semibold uppercase tracking-wider text-slate-500">{{ $group['heading'] }}</p>
                @endif

                @foreach ($visible as $item)
                    @php $active = request()->routeIs($item['pattern']); @endphp
                    <a href="{{ route($item['route']) }}"
                       @class([
                           'group relative flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium transition-colors',
                           'bg-brand-600 text-white shadow-sm shadow-brand-900/30' => $active,
                           'text-slate-400 hover:bg-white/5 hover:text-white' => ! $active,
                       ])>
                        @if ($active)
                            <span class="absolute -left-3 top-1/2 h-6 w-1 -translate-y-1/2 rounded-r-full bg-brand-300"></span>
                        @endif
                        <svg class="h-5 w-5 shrink-0 {{ $active ? 'text-white' : 'text-slate-500 group-hover:text-brand-300' }}" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="{{ $item['icon'] }}"/>
                        </svg>
                        <span class="truncate">{{ $item['label'] }}</span>

                        @if (($item['badge'] ?? null) === 'inquiries' && $unreadInquiries)
                            <span class="ml-auto flex h-5 min-w-5 items-center justify-center rounded-full bg-red-500 px-1.5 text-[11px] font-semibold text-white">{{ $unreadInquiries }}</span>
                        @endif
                    </a>
                @endforeach
            </div>
        @endforeach
    </nav>

    {{-- Footer --}}
    <div class="shrink-0 space-y-1 border-t border-white/5 px-3 py-4">
        <a href="{{ route('home') }}" target="_blank" rel="noopener"
           class="group flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium text-slate-400 transition-colors hover:bg-white/5 hover:text-white">
            <svg class="h-5 w-5 shrink-0 text-slate-500 group-hover:text-brand-300" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
            <span class="truncate">View Website</span>
            <svg class="ml-auto h-4 w-4 shrink-0 text-slate-600 transition-transform group-hover:translate-x-0.5 group-hover:text-slate-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
        </a>

        @if ($user)
            <div class="mt-2 flex items-center gap-3 rounded-lg bg-white/5 px-3 py-2.5">
                <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-gradient-to-br from-brand-400 to-brand-600 text-sm font-semibold text-white">
                    {{ strtoupper(substr($user->name ?? 'A', 0, 1)) }}
                </span>
                <span class="flex flex-col overflow-hidden">
                    <span class="truncate text-sm font-semibold text-white">{{ $user->name ?? 'Admin' }}</span>
                    <span class="truncate text-[11px] text-slate-500">{{ $user->role_label ?? 'Staff' }}</span>
                </span>
            </div>
        @endif
    </div>
</aside>
