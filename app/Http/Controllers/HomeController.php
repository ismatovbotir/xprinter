<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function __invoke(): View
    {
        $categories = Category::with('translations')->withCount('products')->get();

        $featured = Product::with(['translations', 'category.translations'])
            ->latest()
            ->limit(6)
            ->get();

        return view('welcome', compact('categories', 'featured'));
    }
}
