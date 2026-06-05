<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin Login — {{ setting('site_name', config('app.name')) }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="flex min-h-screen items-center justify-center bg-slate-900 px-4 font-sans"
      style="background-image: linear-gradient(rgba(15,23,42,0.85), rgba(15,23,42,0.9)), url('https://images.unsplash.com/photo-1469854523086-cc02fe5d8800?auto=format&fit=crop&w=1920&q=80'); background-size: cover; background-position: center;">
    <div class="w-full max-w-md">
        <div class="mb-6 text-center text-white">
            <a href="{{ route('home') }}" class="inline-flex items-center gap-2 text-2xl font-bold">
                <span class="flex h-10 w-10 items-center justify-center rounded-lg bg-brand-600">✈</span>
                {{ setting('site_name', config('app.name')) }}
            </a>
            <p class="mt-2 text-sm text-white/70">Administrator Panel</p>
        </div>

        <div class="rounded-2xl bg-white p-8 shadow-2xl">
            <h1 class="mb-1 text-xl font-bold text-slate-900">Welcome back</h1>
            <p class="mb-6 text-sm text-slate-500">Sign in to manage your website.</p>

            @if (session('error'))
                <div class="mb-4 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">{{ session('error') }}</div>
            @endif
            @if (session('success'))
                <div class="mb-4 rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700">{{ session('success') }}</div>
            @endif

            <form method="POST" action="{{ route('admin.login.attempt') }}" class="space-y-4">
                @csrf
                <div>
                    <label class="form-label" for="email">Email Address</label>
                    <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus
                           class="form-input-base @error('email') border-red-400 @enderror" placeholder="admin@example.com">
                    @error('email')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="form-label" for="password">Password</label>
                    <input id="password" type="password" name="password" required
                           class="form-input-base @error('password') border-red-400 @enderror" placeholder="••••••••">
                    @error('password')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                </div>
                <div class="flex items-center justify-between">
                    <label class="flex items-center gap-2 text-sm text-slate-600">
                        <input type="checkbox" name="remember" class="rounded border-slate-300 text-brand-600 focus:ring-brand-500">
                        Remember me
                    </label>
                </div>
                <button type="submit" class="btn-primary w-full">Sign In</button>
            </form>
        </div>

        <p class="mt-6 text-center text-xs text-white/60">
            &larr; <a href="{{ route('home') }}" class="hover:text-white">Back to website</a>
        </p>
    </div>
</body>
</html>
