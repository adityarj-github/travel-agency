@extends('layouts.frontend')

@section('title', 'Travel Blog — ' . setting('site_name', config('app.name')))

@section('content')
    <x-page-hero title="Travel Blog" subtitle="Stories, tips and inspiration for your next adventure."
                 :breadcrumbs="['Blog' => null]" />

    <section class="py-16">
        <div class="container grid gap-10 lg:grid-cols-4">
            <div class="lg:col-span-3">
                @if ($blogs->isNotEmpty())
                    <div class="grid gap-7 sm:grid-cols-2">
                        @foreach ($blogs as $blog)
                            <x-blog-card :blog="$blog" />
                        @endforeach
                    </div>
                    <div class="mt-10">{{ $blogs->links() }}</div>
                @else
                    <div class="rounded-2xl border border-dashed border-slate-200 p-16 text-center">
                        <p class="text-lg font-semibold text-slate-700">No articles found</p>
                        <p class="mt-1 text-sm text-slate-400">Check back soon for travel stories and guides.</p>
                    </div>
                @endif
            </div>

            {{-- Sidebar --}}
            <aside class="space-y-8">
                <div class="rounded-2xl border border-slate-100 bg-white p-6">
                    <h3 class="mb-3 font-bold text-slate-900">Search</h3>
                    <form method="GET" action="{{ route('blog.index') }}" class="flex gap-2">
                        <input type="text" name="q" value="{{ request('q') }}" class="form-input-base" placeholder="Search...">
                        <button class="btn-primary !px-4 !py-2">Go</button>
                    </form>
                </div>

                @if ($categories->isNotEmpty())
                <div class="rounded-2xl border border-slate-100 bg-white p-6">
                    <h3 class="mb-3 font-bold text-slate-900">Categories</h3>
                    <ul class="space-y-2 text-sm">
                        <li><a href="{{ route('blog.index') }}" class="flex justify-between text-slate-600 hover:text-brand-600"><span>All Posts</span></a></li>
                        @foreach ($categories as $cat)
                            <li>
                                <a href="{{ route('blog.index', ['category' => $cat->slug]) }}"
                                   class="flex justify-between {{ request('category') == $cat->slug ? 'font-semibold text-brand-600' : 'text-slate-600 hover:text-brand-600' }}">
                                    <span>{{ $cat->name }}</span>
                                    <span class="text-slate-400">{{ $cat->blogs_count }}</span>
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>
                @endif

                @if ($recentPosts->isNotEmpty())
                <div class="rounded-2xl border border-slate-100 bg-white p-6">
                    <h3 class="mb-3 font-bold text-slate-900">Recent Posts</h3>
                    <ul class="space-y-4">
                        @foreach ($recentPosts as $post)
                            <li class="flex gap-3">
                                <a href="{{ route('blog.show', $post->slug) }}" class="shrink-0">
                                    <img src="{{ $post->featured_image_url }}" loading="lazy" decoding="async" width="56" height="56" class="h-14 w-14 rounded-lg object-cover" alt="">
                                </a>
                                <div>
                                    <a href="{{ route('blog.show', $post->slug) }}" class="line-clamp-2 text-sm font-medium text-slate-700 hover:text-brand-600">{{ $post->title }}</a>
                                    <p class="text-xs text-slate-400">{{ optional($post->published_at ?? $post->created_at)->format('M d, Y') }}</p>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                </div>
                @endif
            </aside>
        </div>
    </section>
@endsection
