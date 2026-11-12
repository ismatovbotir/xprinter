<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Cache;

class SitemapController extends Controller
{
    public function index(): Response
    {
        $xml = Cache::remember('sitemap.xml', 3600, function () {
            $urls = [
                ['loc' => route('home'), 'priority' => '1.0', 'changefreq' => 'daily'],
                ['loc' => route('catalog'), 'priority' => '0.9', 'changefreq' => 'daily'],
                ['loc' => route('partners.index'), 'priority' => '0.8', 'changefreq' => 'weekly'],
                ['loc' => route('about'), 'priority' => '0.5', 'changefreq' => 'monthly'],
                ['loc' => route('contact'), 'priority' => '0.5', 'changefreq' => 'monthly'],
            ];

            foreach (Category::all() as $category) {
                $urls[] = [
                    'loc'        => route('catalog.category', $category->slug),
                    'priority'   => '0.8',
                    'changefreq' => 'weekly',
                ];
            }

            foreach (Product::with('category')->get() as $product) {
                $urls[] = [
                    'loc'        => route('products.show', ['category' => $product->category->slug, 'slug' => $product->slug]),
                    'lastmod'    => $product->updated_at?->toAtomString(),
                    'priority'   => '0.7',
                    'changefreq' => 'weekly',
                ];
            }

            foreach (\App\Models\Company::where('status', 'approved')->whereNotNull('slug')->get(['slug', 'updated_at']) as $company) {
                $urls[] = [
                    'loc'        => route('partners.show', $company->slug),
                    'lastmod'    => $company->updated_at?->toAtomString(),
                    'priority'   => '0.6',
                    'changefreq' => 'weekly',
                ];
            }

            return '<' . '?xml version="1.0" encoding="UTF-8"?' . '>' . "\n" . view('sitemap', compact('urls'))->render();
        });

        return response($xml, 200)->header('Content-Type', 'application/xml');
    }
}
