<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProductController extends Controller
{
    public function index(Request $request): View
    {
        $categories = Category::with('translations')->get();

        $products = Product::with(['translations', 'category.translations'])
            ->when($request->search, fn($q) =>
                $q->where('model_number', 'like', "%{$request->search}%")
                  ->orWhereHas('translations', fn($t) =>
                      $t->where('name', 'like', "%{$request->search}%")
                  )
            )
            ->when($request->category_id, fn($q) =>
                $q->where('category_id', $request->category_id)
            )
            ->latest()
            ->paginate(20)
            ->withQueryString();

        return view('admin.products.index', compact('products', 'categories'));
    }

    public function create(): View
    {
        $categories = Category::with('translations')->get();
        return view('admin.products.form', compact('categories'));
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'category_id'    => 'required|exists:categories,id',
            'model_number'   => 'required|string|max:100',
            'slug'           => 'required|string|max:150|unique:products,slug|regex:/^[a-z0-9-]+$/',
            'name_uz'        => 'required|string|max:255',
            'name_ru'        => 'required|string|max:255',
            'name_en'        => 'required|string|max:255',
            'description_uz' => 'nullable|string',
            'description_ru' => 'nullable|string',
            'description_en' => 'nullable|string',
        ]);

        $product = Product::create([
            'category_id'  => $request->category_id,
            'slug'         => $request->slug,
            'model_number' => $request->model_number,
        ]);

        foreach (['uz', 'ru', 'en'] as $lang) {
            $product->translations()->create([
                'lang'        => $lang,
                'name'        => $request->{"name_{$lang}"},
                'description' => $request->{"description_{$lang}"},
            ]);
        }

        return redirect()->route('admin.products.index')
            ->with('success', "«{$request->model_number}» mahsuloti qo'shildi");
    }

    public function edit(Product $product): View
    {
        $product->load('translations', 'category');
        $categories = Category::with('translations')->get();
        return view('admin.products.form', compact('product', 'categories'));
    }

    public function update(Request $request, Product $product): RedirectResponse
    {
        $request->validate([
            'category_id'    => 'required|exists:categories,id',
            'model_number'   => 'required|string|max:100',
            'slug'           => "required|string|max:150|unique:products,slug,{$product->id}|regex:/^[a-z0-9-]+$/",
            'name_uz'        => 'required|string|max:255',
            'name_ru'        => 'required|string|max:255',
            'name_en'        => 'required|string|max:255',
            'description_uz' => 'nullable|string',
            'description_ru' => 'nullable|string',
            'description_en' => 'nullable|string',
        ]);

        $product->update([
            'category_id'  => $request->category_id,
            'slug'         => $request->slug,
            'model_number' => $request->model_number,
        ]);

        foreach (['uz', 'ru', 'en'] as $lang) {
            $product->translations()->updateOrCreate(
                ['lang' => $lang],
                [
                    'name'        => $request->{"name_{$lang}"},
                    'description' => $request->{"description_{$lang}"},
                ]
            );
        }

        return redirect()->route('admin.products.index')
            ->with('success', "«{$request->model_number}» yangilandi");
    }

    public function destroy(Product $product): RedirectResponse
    {
        $name = $product->model_number;
        $product->delete();

        return redirect()->route('admin.products.index')
            ->with('success', "«{$name}» o'chirildi");
    }
}
