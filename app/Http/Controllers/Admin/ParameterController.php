<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Parameter;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ParameterController extends Controller
{
    public function index(Request $request): View
    {
        $parameters = Parameter::with('translations')
            ->withCount('categories')
            ->when($request->search, fn($q) =>
                $q->whereHas('translations', fn($t) =>
                    $t->where('name', 'like', "%{$request->search}%")
                )
            )
            ->latest()
            ->paginate(20)
            ->withQueryString();

        return view('admin.parameters.index', compact('parameters'));
    }

    public function create(): View
    {
        $categories = Category::with('translations')->get();
        return view('admin.parameters.form', compact('categories'));
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name_uz'       => 'required|string|max:255',
            'name_ru'       => 'required|string|max:255',
            'category_ids'  => 'nullable|array',
            'category_ids.*'=> 'exists:categories,id',
        ]);

        $parameter = Parameter::create([]);

        foreach (['uz', 'ru', 'en'] as $lang) {
            $parameter->translations()->create([
                'lang' => $lang,
                'name' => $request->{"name_{$lang}"},
            ]);
        }

        if ($request->filled('category_ids')) {
            $sync = [];
            foreach ($request->category_ids as $i => $catId) {
                $sync[$catId] = ['sort_order' => $i];
            }
            $parameter->categories()->sync($sync);
        }

        return redirect()->route('admin.parameters.index')
            ->with('success', "«{$request->name_uz}» parametri qo'shildi");
    }

    public function edit(Parameter $parameter): View
    {
        $parameter->load('translations', 'categories');
        $categories = Category::with('translations')->get();
        return view('admin.parameters.form', compact('parameter', 'categories'));
    }

    public function update(Request $request, Parameter $parameter): RedirectResponse
    {
        $request->validate([
            'name_uz'       => 'required|string|max:255',
            'name_ru'       => 'required|string|max:255',
            'category_ids'  => 'nullable|array',
            'category_ids.*'=> 'exists:categories,id',
        ]);

        foreach (['uz', 'ru', 'en'] as $lang) {
            $parameter->translations()->updateOrCreate(
                ['lang' => $lang],
                ['name' => $request->{"name_{$lang}"}]
            );
        }

        $sync = [];
        foreach ($request->category_ids ?? [] as $i => $catId) {
            $sync[$catId] = ['sort_order' => $i];
        }
        $parameter->categories()->sync($sync);

        return redirect()->route('admin.parameters.index')
            ->with('success', "«{$request->name_uz}» yangilandi");
    }

    public function destroy(Parameter $parameter): RedirectResponse
    {
        $name = $parameter->translations->firstWhere('lang', 'uz')?->name ?? "#{$parameter->id}";
        $parameter->delete();

        return redirect()->route('admin.parameters.index')
            ->with('success', "«{$name}» o'chirildi");
    }
}
