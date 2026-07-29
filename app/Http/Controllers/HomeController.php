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

        return view('welcome', compact('banners', 'categories', 'featured', 'content'));
    }
}
