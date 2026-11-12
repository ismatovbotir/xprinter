<?php

namespace App\Http\Controllers;

use App\Models\Banner;
use App\Models\Category;
use App\Models\HomeContent;
use App\Models\Product;
use Illuminate\Support\Facades\Cache;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function __invoke(): View
    {
        $banners = Banner::active()->get();

        $categories = Cache::remember('catalog.categories', 3600, fn() =>
            Category::with('translations')->withCount('products')->get()
        );

        $featured = Cache::remember('home.featured', 3600, fn() =>
            Product::with(['translations', 'category.translations'])->latest()->limit(6)->get()
        );

        $content = HomeContent::current();

        [$heroSlides, $scenarioModels] = $this->showcase($content, app()->getLocale());

        return view('welcome', compact('banners', 'categories', 'featured', 'content', 'heroSlides', 'scenarioModels'));
    }

    /**
     * Products the admin picked for the hero carousel and the use-case cards
     * (Admin → Bosh sahifa), resolved to plain arrays for the view.
     */
    private function showcase(HomeContent $content, string $lang): array
    {
        return Cache::remember("home.showcase.{$lang}", 600, function () use ($content, $lang) {
            $heroIds     = $content->heroProductIds();
            $scenarioIds = $content->scenarioProductIds();

            $products = Product::with([
                'translations',
                'category.translations',
                'parameterValues.translations',
                'parameterValues.parameter.translations',
            ])
                ->withCount(['companyProducts as sellers_count' => fn($q) => $q->where('is_available', true)
                    ->whereHas('company', fn($c) => $c->where('status', 'approved'))])
                ->whereIn('id', array_unique(array_merge($heroIds, ...array_values($scenarioIds))))
                ->get()
                ->keyBy('id');

            $url = fn(Product $p) => $p->category
                ? route('products.show', [$p->category->slug, $p->slug])
                : route('catalog');

            $name = fn($translations) => $translations->firstWhere('lang', $lang)?->name
                ?? $translations->first()?->name;

            $hero = collect($heroIds)->map(fn($id) => $products[$id] ?? null)->filter()->map(fn(Product $p) => [
                'model'   => $p->model_number,
                'type'    => $p->category ? $name($p->category->translations) : null,
                'specs'   => $p->parameterValues->mapWithKeys(fn($v) => [
                    $name($v->parameter?->translations ?? collect()) ?? '—' => $name($v->translations),
                ])->filter()->take(5)->all(),
                'sellers' => $p->sellers_count,
                'url'     => $url($p),
            ])->values()->all();

            $scenarios = collect($scenarioIds)->map(fn($ids) => collect($ids)
                ->map(fn($id) => $products[$id] ?? null)->filter()
                ->map(fn(Product $p) => ['model' => $p->model_number, 'url' => $url($p)])
                ->values()->all()
            )->all();

            return [$hero, $scenarios];
        });
    }

    public static function forgetShowcase(): void
    {
        foreach (['uz', 'ru', 'en'] as $lang) {
            Cache::forget("home.showcase.{$lang}");
        }
    }
}
