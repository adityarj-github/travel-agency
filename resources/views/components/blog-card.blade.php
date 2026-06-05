@props(['blog'])

<article class="group flex h-full flex-col overflow-hidden rounded-2xl bg-white card-shadow transition hover:-translate-y-1">
    <a href="{{ route('blog.show', $blog->slug) }}" class="relative block h-48 overflow-hidden">
        <img src="{{ $blog->featured_image_url }}" alt="{{ $blog->title }}" loading="lazy" decoding="async"
             class="h-full w-full object-cover transition duration-500 group-hover:scale-110">
        @if ($blog->category)
            <span class="absolute left-3 top-3 rounded-full bg-brand-600 px-3 py-1 text-xs font-semibold text-white">
                {{ $blog->category->name }}
            </span>
        @endif
    </a>
    <div class="flex flex-1 flex-col p-5">
        <div class="mb-2 flex items-center gap-3 text-xs text-slate-400">
            <span>{{ optional($blog->published_at ?? $blog->created_at)->format('M d, Y') }}</span>
            @if ($blog->author)<span>• {{ $blog->author }}</span>@endif
        </div>
        <h3 class="mb-2 text-lg font-bold leading-snug text-slate-900">
            <a href="{{ route('blog.show', $blog->slug) }}" class="transition hover:text-brand-600">{{ $blog->title }}</a>
        </h3>
        <p class="mb-4 line-clamp-3 flex-1 text-sm text-slate-500">{{ $blog->excerpt }}</p>
        <a href="{{ route('blog.show', $blog->slug) }}" class="mt-auto inline-flex items-center gap-1 text-sm font-semibold text-brand-600 hover:gap-2">
            Read More →
        </a>
    </div>
</article>
