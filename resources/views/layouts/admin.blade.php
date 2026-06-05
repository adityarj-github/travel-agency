<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') — Admin | {{ setting('site_name', config('app.name')) }}</title>
    @if (setting('favicon'))<link rel="icon" href="{{ setting_image('favicon') }}">@endif
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('head')
</head>
<body class="min-h-screen bg-slate-100 font-sans text-slate-700" x-data="{ sidebarOpen: false }">
    <div class="flex min-h-screen">
        @include('admin.partials.sidebar')

        {{-- Mobile overlay --}}
        <div x-show="sidebarOpen" x-cloak @click="sidebarOpen = false"
             class="fixed inset-0 z-30 bg-slate-900/50 lg:hidden"></div>

        <div class="flex w-full flex-1 flex-col lg:pl-64">
            @include('admin.partials.topbar')

            <main class="flex-1 p-4 sm:p-6 lg:p-8">
                <div class="mb-6 flex flex-col gap-1 sm:flex-row sm:items-center sm:justify-between">
                    <div>
                        <h1 class="text-2xl font-bold text-slate-900">@yield('page_title', 'Dashboard')</h1>
                        @hasSection('breadcrumb')
                            <p class="text-sm text-slate-400">@yield('breadcrumb')</p>
                        @endif
                    </div>
                    <div>@yield('page_actions')</div>
                </div>

                @include('admin.partials.flash')

                @yield('content')
            </main>

            <footer class="border-t border-slate-200 bg-white px-6 py-4 text-center text-xs text-slate-400">
                &copy; {{ date('Y') }} {{ setting('site_name', config('app.name')) }} Admin Panel
            </footer>
        </div>
    </div>
    @stack('scripts')
</body>
</html>
