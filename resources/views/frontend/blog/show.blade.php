@extends('layouts.frontend')

@section('title', ($blog->meta_title ?: $blog->title) . ' — ' . setting('site_name', config('app.name')))
@section('meta_description', $blog->meta_description ?: \Illuminate\Support\Str::limit(strip_tags($blog->excerpt), 150))
@section('og_image', $blog->featured_image_url)
@section('og_type', 'article')

@push('schema')
@php
    $articleSchema = [
        '@context' => 'https://schema.org',
        '@type' => 'Article',
        'headline' => $blog->title,
        'image' => [$blog->featured_image_url],
        'datePublished' => optional($blog->published_at ?? $blog->created_at)->toAtomString(),
        'dateModified' => optional($blog->updated_at)->toAtomString(),
        'author' => ['@type' => 'Person', 'name' => $blog->author ?: setting('site_name', config('app.name'))],
        'publisher' => ['@type' => 'Organization', 'name' => setting('site_name', config('app.name'))],
        'description' => \Illuminate\Support\Str::limit(strip_tags($blog->excerpt), 200),
        'mainEntityOfPage' => route('blog.show', $blog->slug),
    ];
@endphp
<script type="application/ld+json">{!! json_encode($articleSchema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}</script>
@endpush

@section('content')
    <x-page-hero :title="$blog->title" :image="$blog->featured_image_url"
                 :breadcrumbs="['Blog' => route('blog.index'), \Illuminate\Support\Str::limit($blog->title, 30) => null]" />

    <section class="py-16">
        <div class="container grid gap-10 lg:grid-cols-4">
            <article class="lg:col-span-3">
                <div class="mb-6 flex flex-wrap items-center gap-4 text-sm text-slate-400">
                    @if ($blog->category)
                        <a href="{{ route('blog.index', ['category' => $blog->category->slug]) }}" class="rounded-full bg-brand-50 px-3 py-1 font-medium text-brand-700">{{ $blog->category->name }}</a>
                    @endif
                    <span>📅 {{ optional($blog->published_at ?? $blog->created_at)->format('F d, Y') }}</span>
                    @if ($blog->author)<span>✍ {{ $blog->author }}</span>@endif
                    <span>👁 {{ number_format($blog->views) }} views</span>
                </div>

                <img src="{{ $blog->featured_image_url }}" alt="{{ $blog->title }}" fetchpriority="high" decoding="async" class="mb-8 w-full rounded-2xl object-cover">

                @if ($blog->excerpt)
                    <p class="mb-6 text-lg font-medium text-slate-600">{{ $blog->excerpt }}</p>
                @endif

                <div class="prose-content max-w-none">{!! $blog->content !!}</div>

                {{-- Social share --}}
                <div class="mt-10 flex items-center gap-3 border-t border-slate-100 pt-6">
                    <span class="text-sm font-semibold text-slate-700">Share:</span>
                    @php $url = urlencode(request()->fullUrl()); $title = urlencode($blog->title); @endphp
                    <a href="https://www.facebook.com/sharer/sharer.php?u={{ $url }}" target="_blank" rel="noopener" class="rounded-full bg-slate-100 px-4 py-2 text-sm hover:bg-brand-600 hover:text-white">Facebook</a>
                    <a href="https://twitter.com/intent/tweet?url={{ $url }}&text={{ $title }}" target="_blank" rel="noopener" class="rounded-full bg-slate-100 px-4 py-2 text-sm hover:bg-brand-600 hover:text-white">Twitter / X</a>
                    <a href="https://api.whatsapp.com/send?text={{ $title }}%20{{ $url }}" target="_blank" rel="noopener" class="rounded-full bg-slate-100 px-4 py-2 text-sm hover:bg-brand-600 hover:text-white">WhatsApp</a>
                    <a href="https://www.linkedin.com/sharing/share-offsite/?url={{ $url }}" target="_blank" rel="noopener" class="rounded-full bg-slate-100 px-4 py-2 text-sm hover:bg-brand-600 hover:text-white">LinkedIn</a>
                </div>
            </article>

            {{-- Sidebar --}}
            <aside class="space-y-8">
                @if ($categories->isNotEmpty())
                <div class="rounded-2xl border border-slate-100 bg-white p-6">
                    <h3 class="mb-3 font-bold text-slate-900">Categories</h3>
                    <ul class="space-y-2 text-sm">
                        @foreach ($categories as $cat)
                            <li><a href="{{ route('blog.index', ['category' => $cat->slug]) }}" class="flex justify-between text-slate-600 hover:text-brand-600"><span>{{ $cat->name }}</span><span class="text-slate-400">{{ $cat->blogs_count }}</span></a></li>
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
                                <a href="{{ route('blog.show', $post->slug) }}" class="shrink-0"><img src="{{ $post->featured_image_url }}" loading="lazy" decoding="async" width="56" height="56" class="h-14 w-14 rounded-lg object-cover" alt=""></a>
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

    {{-- Related --}}
    @if ($related->isNotEmpty())
    <section class="bg-slate-50 py-16">
        <div class="container">
            <x-section-heading title="Related Articles" :center="false" />
            <div class="mt-8 grid gap-7 sm:grid-cols-2 lg:grid-cols-3">
                @foreach ($related as $rel)
                    <x-blog-card :blog="$rel" />
                @endforeach
            </div>
        </div>
    </section>
    @endif
@endsection
