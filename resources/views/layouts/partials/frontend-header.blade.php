@php
    $navLinks = [
        ['label' => 'Home', 'route' => 'home'],
        ['label' => 'About', 'route' => 'about'],
        ['label' => 'Packages', 'route' => 'packages.index'],
        ['label' => 'Destinations', 'route' => 'destinations.index'],
        ['label' => 'Blog', 'route' => 'blog.index'],
        ['label' => 'Gallery', 'route' => 'gallery'],
        ['label' => 'Contact', 'route' => 'contact'],
    ];
@endphp

<header x-data="{ open: false, scrolled: false }"
        @scroll.window="scrolled = (window.pageYOffset > 30)"
        class="fixed inset-x-0 top-0 z-40 transition-all duration-300"
        :class="scrolled ? 'bg-white/95 shadow-md backdrop-blur' : 'bg-transparent'">
    <nav class="container flex h-16 items-center justify-between md:h-20">
        {{-- Logo --}}
        <a href="{{ route('home') }}" class="flex items-center gap-2">
            @if (setting('logo'))
                <img src="{{ setting_image('logo') }}" alt="{{ setting('site_name', config('app.name')) }}" class="h-10 w-auto">
            @else
                <span class="text-2xl font-bold" :class="scrolled ? 'text-brand-700' : 'text-white drop-shadow'">
                    {{ setting('site_name', config('app.name')) }}
                </span>
            @endif
        </a>

        {{-- Desktop nav --}}
        <ul class="hidden items-center gap-7 lg:flex">
            @foreach ($navLinks as $link)
                @php $active = request()->routeIs($link['route']) || ($link['route'] === 'packages.index' && request()->routeIs('packages.*')); @endphp
                <li>
                    <a href="{{ route($link['route']) }}"
                       class="text-sm font-medium transition hover:text-brand-500"
                       :class="scrolled ? '{{ $active ? 'text-brand-600' : 'text-slate-700' }}' : '{{ $active ? 'text-white' : 'text-white/90' }}'">
                        {{ $link['label'] }}
                    </a>
                </li>
            @endforeach
        </ul>

        <div class="hidden items-center gap-3 lg:flex">
            @auth
                @if (auth()->user()->isCustomer())
                    <div class="relative" x-data="{ acct: false }">
                        <button @click="acct = !acct" class="flex items-center gap-2 text-sm font-medium"
                                :class="scrolled ? 'text-slate-700' : 'text-white'">
                            <span class="flex h-8 w-8 items-center justify-center rounded-full bg-brand-100 text-xs font-semibold text-brand-700">
                                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                            </span>
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
                        </button>
                        <div x-show="acct" x-cloak @click.outside="acct = false"
                             class="absolute right-0 mt-2 w-48 rounded-lg border border-slate-100 bg-white py-1 shadow-lg">
                            <a href="{{ route('account.dashboard') }}" class="block px-4 py-2 text-sm text-slate-600 hover:bg-slate-50">My Account</a>
                            <a href="{{ route('account.bookings') }}" class="block px-4 py-2 text-sm text-slate-600 hover:bg-slate-50">My Bookings</a>
                            <a href="{{ route('account.wishlist') }}" class="block px-4 py-2 text-sm text-slate-600 hover:bg-slate-50">Wishlist</a>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="block w-full px-4 py-2 text-left text-sm text-red-600 hover:bg-red-50">Logout</button>
                            </form>
                        </div>
                    </div>
                @else
                    <a href="{{ route('admin.dashboard') }}" class="text-sm font-semibold"
                       :class="scrolled ? 'text-brand-700' : 'text-white'">Admin</a>
                @endif
            @else
                <a href="{{ route('login') }}" class="text-sm font-medium"
                   :class="scrolled ? 'text-slate-700' : 'text-white'">Sign In</a>
            @endauth
            <a href="{{ route('booking.create') }}" class="btn-primary !px-5 !py-2.5">Book Now</a>
        </div>

        {{-- Mobile toggle --}}
        <button @click="open = !open" class="lg:hidden" aria-label="Toggle menu">
            <svg class="h-7 w-7" :class="scrolled || open ? 'text-slate-800' : 'text-white'" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path x-show="!open" stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"/>
                <path x-show="open" x-cloak stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </button>
    </nav>

    {{-- Mobile menu --}}
    <div x-show="open" x-cloak x-collapse class="bg-white shadow-lg lg:hidden">
        <ul class="container space-y-1 py-4">
            @foreach ($navLinks as $link)
                <li>
                    <a href="{{ route($link['route']) }}" class="block rounded-lg px-3 py-2 text-slate-700 hover:bg-brand-50 hover:text-brand-700">
                        {{ $link['label'] }}
                    </a>
                </li>
            @endforeach
            <li class="border-t border-slate-100 pt-2">
                @auth
                    @if (auth()->user()->isCustomer())
                        <a href="{{ route('account.dashboard') }}" class="block rounded-lg px-3 py-2 text-slate-700 hover:bg-brand-50 hover:text-brand-700">My Account</a>
                        <a href="{{ route('account.bookings') }}" class="block rounded-lg px-3 py-2 text-slate-700 hover:bg-brand-50 hover:text-brand-700">My Bookings</a>
                        <a href="{{ route('account.wishlist') }}" class="block rounded-lg px-3 py-2 text-slate-700 hover:bg-brand-50 hover:text-brand-700">Wishlist</a>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="block w-full rounded-lg px-3 py-2 text-left text-red-600 hover:bg-red-50">Logout</button>
                        </form>
                    @else
                        <a href="{{ route('admin.dashboard') }}" class="block rounded-lg px-3 py-2 text-slate-700 hover:bg-brand-50 hover:text-brand-700">Admin Panel</a>
                    @endif
                @else
                    <a href="{{ route('login') }}" class="block rounded-lg px-3 py-2 text-slate-700 hover:bg-brand-50 hover:text-brand-700">Sign In</a>
                    <a href="{{ route('register') }}" class="block rounded-lg px-3 py-2 text-slate-700 hover:bg-brand-50 hover:text-brand-700">Create Account</a>
                @endauth
            </li>
            <li class="pt-2">
                <a href="{{ route('booking.create') }}" class="btn-primary w-full">Book Now</a>
            </li>
        </ul>
    </div>
</header>
