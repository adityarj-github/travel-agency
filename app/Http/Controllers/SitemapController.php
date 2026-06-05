<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use App\Models\Destination;
use App\Models\Package;
use Illuminate\Http\Response;

class SitemapController extends Controller
{
    public function index(): Response
    {
        $urls = [];

        // Static pages.
        foreach (['home', 'about', 'packages.index', 'destinations.index', 'blog.index', 'gallery', 'contact'] as $name) {
            $urls[] = ['loc' => route($name), 'priority' => $name === 'home' ? '1.0' : '0.7'];
        }

        // Packages.
        foreach (Package::active()->get(['slug', 'updated_at']) as $package) {
            $urls[] = [
                'loc' => route('packages.show', $package->slug),
                'lastmod' => $package->updated_at?->toAtomString(),
                'priority' => '0.8',
            ];
        }

        // Destinations.
        foreach (Destination::active()->get(['slug', 'updated_at']) as $destination) {
            $urls[] = [
                'loc' => route('destinations.show', $destination->slug),
                'lastmod' => $destination->updated_at?->toAtomString(),
                'priority' => '0.6',
            ];
        }

        // Blog posts.
        foreach (Blog::published()->get(['slug', 'updated_at']) as $blog) {
            $urls[] = [
                'loc' => route('blog.show', $blog->slug),
                'lastmod' => $blog->updated_at?->toAtomString(),
                'priority' => '0.5',
            ];
        }

        return response()
            ->view('seo.sitemap', compact('urls'))
            ->header('Content-Type', 'application/xml');
    }

    public function robots(): Response
    {
        $lines = [
            'User-agent: *',
            'Disallow: /admin',
            'Disallow: /account',
            'Allow: /',
            '',
            'Sitemap: ' . route('sitemap'),
        ];

        return response(implode("\n", $lines), 200)
            ->header('Content-Type', 'text/plain');
    }
}
