<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\View\View;

class TelegramAppController extends Controller
{
    public function index(Request $request): View
    {
        $categories = Cache::remember('catalog.categories', 3600, fn() =>
            Category::with('translations')->withCount('products')->get()
        );

        $activeCategory = null;
        $products = collect();

        if ($slug = $request->query('category')) {
            $activeCategory = Category::where('slug', $slug)->first();

            if ($activeCategory) {
                $products = Product::with(['translations', 'photos'])
                    ->where('category_id', $activeCategory->id)
                    ->get();
            }
        }

        return view('telegram.app', compact('categories', 'activeCategory', 'products'));
    }
}
