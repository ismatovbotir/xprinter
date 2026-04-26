<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Parameter;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class CategoryController extends Controller
{
    public function index(Request $request): View
    {
        $categories = Category::with('translations')
            ->withCount('products')
            ->when($request->search, fn($q) =>
                $q->whereHas('translations', fn($t) =>
                    $t->where('name', 'like', "%{$request->search}%")
                )
            )
            ->latest()
            ->paginate(20)
            ->withQueryString();

        return view('admin.categories.index', compact('categories'));
    }

    public function create(): View
    {
        return view('admin.categories.form');
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'slug'    => 'required|string|max:100|unique:categories,slug|regex:/^[a-z0-9-]+$/',
            'name_uz' => 'required|string|max:255',
            'name_ru' => 'required|string|max:255',
            'name_en' => 'required|string|max:255',
        ]);

        $category = Category::create(['slug' => $request->slug]);

        foreach (['uz', 'ru', 'en'] as $lang) {
            $category->translations()->create([
                'lang' => $lang,
                'name' => $request->{"name_{$lang}"},
            ]);
        }

        return redirect()->route('admin.categories.index')
            ->with('success', "«{$request->name_uz}» kategoriyasi qo'shildi");
    }

    public function edit(Category $category): View
    {
        $category->load('translations', 'parameters.translations');
        $allParameters = Parameter::with('translations')->get();
        return view('admin.categories.form', compact('category', 'allParameters'));
    }

    public function update(Request $request, Category $category): RedirectResponse
    {
        $request->validate([
            'slug'    => "required|string|max:100|unique:categories,slug,{$category->id}|regex:/^[a-z0-9-]+$/",
            'name_uz' => 'required|string|max:255',
            'name_ru' => 'required|string|max:255',
            'name_en' => 'required|string|max:255',
        ]);

        $category->update(['slug' => $request->slug]);

        foreach (['uz', 'ru', 'en'] as $lang) {
            $category->translations()->updateOrCreate(
                ['lang' => $lang],
                ['name' => $request->{"name_{$lang}"}]
            );
        }

        return redirect()->route('admin.categories.index')
            ->with('success', "«{$request->name_uz}» yangilandi");
    }

    public function attachParameter(Request $request, Category $category): RedirectResponse
    {
        $request->validate([
            'parameter_id' => 'required|exists:parameters,id',
            'sort_order'   => 'nullable|integer|min:0',
        ]);

        $category->parameters()->syncWithoutDetaching([
            $request->input('parameter_id') => [
                'sort_order' => $request->input('sort_order', 0),
            ],
        ]);

        return back()->with('success', 'Parametr biriktirildi');
    }

    public function detachParameter(Category $category, Parameter $parameter): RedirectResponse
    {
        $category->parameters()->detach($parameter->id);

        return back()->with('success', 'Parametr olib tashlandi');
    }

    public function destroy(Category $category): RedirectResponse
    {
        $name = $category->translations->firstWhere('lang', 'uz')?->name ?? $category->slug;
        $category->delete();

        return redirect()->route('admin.categories.index')
            ->with('success', "«{$name}» o'chirildi");
    }
}
