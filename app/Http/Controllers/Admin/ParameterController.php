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
            'type'          => 'nullable|in:' . implode(',', Parameter::TYPES),
            'category_ids'  => 'nullable|array',
            'category_ids.*'=> 'exists:categories,id',
        ]);

        $parameter = Parameter::create(['type' => $request->input('type', 'string')]);

        foreach (['uz', 'ru', 'en'] as $lang) {
            $parameter->translations()->create([
                'lang' => $lang,
                'name' => $request->{"name_{$lang}"},
            ]);
        }

        if ($parameter->type === 'boolean') {
            $this->seedBooleanValues($parameter);
        }

        if ($request->filled('category_ids')) {
            $sync = [];
            foreach ($request->category_ids as $i => $catId) {
                $sync[$catId] = ['sort_order' => $i];
            }
            $parameter->categories()->sync($sync);
        }

        if ($request->filled('category_id')) {
            return redirect()->route('admin.categories.edit', $request->category_id)
                ->with('success', "«{$request->name_uz}» parametri qo'shildi");
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
            'type'          => 'nullable|in:' . implode(',', Parameter::TYPES),
            'category_ids'  => 'nullable|array',
            'category_ids.*'=> 'exists:categories,id',
        ]);

        foreach (['uz', 'ru', 'en'] as $lang) {
            $parameter->translations()->updateOrCreate(
                ['lang' => $lang],
                ['name' => $request->{"name_{$lang}"}]
            );
        }

        if ($request->filled('type')) {
            $parameter->update(['type' => $request->type]);

            if ($parameter->type === 'boolean' && $parameter->values()->count() === 0) {
                $this->seedBooleanValues($parameter);
            }
        }

        // Only the standalone parameter form submits every category checkbox at once —
        // the quick-edit slide-over on the category page must not wipe out this
        // parameter's other category attachments just because it didn't resend them.
        if ($request->boolean('sync_categories')) {
            $sync = [];
            foreach ($request->category_ids ?? [] as $i => $catId) {
                $sync[$catId] = ['sort_order' => $i];
            }
            $parameter->categories()->sync($sync);
        }

        if ($request->filled('category_id')) {
            return redirect()->route('admin.categories.edit', $request->category_id)
                ->with('success', "«{$request->name_uz}» yangilandi");
        }

        return redirect()->route('admin.parameters.index')
            ->with('success', "«{$request->name_uz}» yangilandi");
    }

    public function destroy(Request $request, Parameter $parameter): RedirectResponse
    {
        $name = $parameter->translations->firstWhere('lang', 'uz')?->name ?? "#{$parameter->id}";
        $categoryId = $request->input('category_id');

        if ($parameter->values()->whereHas('products')->exists() || $parameter->values()->whereHas('companyProductSelections')->exists()) {
            $message = "«{$name}» parametrini o'chirib bo'lmaydi — u mahsulotlarda yoki dilerlar assortimentida ishlatilmoqda";

            return $categoryId
                ? redirect()->route('admin.categories.edit', $categoryId)->with('error', $message)
                : redirect()->route('admin.parameters.index')->with('error', $message);
        }

        $parameter->categories()->detach();
        $parameter->values()->delete();
        $parameter->delete();

        if ($categoryId) {
            return redirect()->route('admin.categories.edit', $categoryId)
                ->with('success', "«{$name}» o'chirildi");
        }

        return redirect()->route('admin.parameters.index')
            ->with('success', "«{$name}» o'chirildi");
    }

    private function seedBooleanValues(Parameter $parameter): void
    {
        foreach ([
            ['uz' => 'Ha',  'ru' => 'Да',  'en' => 'Yes'],
            ['uz' => "Yo'q", 'ru' => 'Нет', 'en' => 'No'],
        ] as $names) {
            $value = $parameter->values()->create([]);

            foreach ($names as $lang => $name) {
                $value->translations()->create(['lang' => $lang, 'name' => $name]);
            }
        }
    }
}
