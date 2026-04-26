<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CatalogController extends Controller
{
    public function index(Request $request): View
    {
        $categories = Category::with('translations')->withCount('products')->get();
        $search     = $request->input('search');

        $products = Product::with(['translations', 'category.translations'])
            ->when($search, fn($q) =>
                $q->where('model_number', 'like', "%{$search}%")
                  ->orWhereHas('translations', fn($t) =>
                      $t->where('name', 'like', "%{$search}%")
                  )
            )
            ->latest()
            ->paginate(12)
            ->withQueryString();

        return view('catalog.index', compact('products', 'categories', 'search'))
            ->with('activeCategory', null);
    }

    public function category(Request $request, string $slug): View
    {
        $categories     = Category::with('translations')->withCount('products')->get();
        $activeCategory = Category::with('translations')->where('slug', $slug)->firstOrFail();
        $search         = $request->input('search');

        $products = Product::with(['translations', 'category.translations'])
            ->where('category_id', $activeCategory->id)
            ->when($search, fn($q) =>
                $q->where('model_number', 'like', "%{$search}%")
                  ->orWhereHas('translations', fn($t) =>
                      $t->where('name', 'like', "%{$search}%")
                  )
            )
            ->latest()
            ->paginate(12)
            ->withQueryString();

        return view('catalog.index', compact('products', 'categories', 'activeCategory', 'search'));
    }

    public function show(string $category, string $slug): View
    {
        $product = Product::with([
            'translations',
            'category.translations',
            'parameterValues.parameter.translations',
            'parameterValues.translations',
        ])->where('slug', $slug)->firstOrFail();

        $related = Product::with(['translations', 'category.translations'])
            ->where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->limit(3)
            ->get();

        return view('catalog.show', compact('product', 'related'));
    }
}
