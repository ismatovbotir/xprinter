<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Parameter;
use App\Models\ParameterValue;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ParameterValueController extends Controller
{
    public function store(Request $request, Parameter $parameter): RedirectResponse
    {
        [$names, $label] = $this->resolveNames($request, $parameter);

        $value = $parameter->values()->create([]);

        foreach ($names as $lang => $name) {
            $value->translations()->create(['lang' => $lang, 'name' => $name]);
        }

        return $this->redirectBack($request, success: "«{$label}» qiymati qo'shildi");
    }

    public function update(Request $request, Parameter $parameter, ParameterValue $value): RedirectResponse
    {
        [$names, $label] = $this->resolveNames($request, $parameter);

        foreach ($names as $lang => $name) {
            $value->translations()->updateOrCreate(['lang' => $lang], ['name' => $name]);
        }

        return $this->redirectBack($request, success: "«{$label}» yangilandi");
    }

    public function destroy(Request $request, Parameter $parameter, ParameterValue $value): RedirectResponse
    {
        $name = $value->translations->firstWhere('lang', 'uz')?->name ?? "#{$value->id}";

        if ($value->products()->exists() || $value->companyProductSelections()->exists()) {
            return $this->redirectBack($request, error: "«{$name}» qiymatini o'chirib bo'lmaydi — u mahsulotlarda yoki dilerlar assortimentida ishlatilmoqda");
        }

        $value->delete();

        return $this->redirectBack($request, success: "«{$name}» o'chirildi");
    }

    /**
     * Integer-type parameters take a single numeric input, replicated across
     * all three languages — a number reads the same regardless of locale.
     * String/boolean types keep the per-language translated labels.
     *
     * @return array{0: array<string,string>, 1: string}
     */
    private function resolveNames(Request $request, Parameter $parameter): array
    {
        if ($parameter->type === 'integer') {
            $request->validate(['value' => 'required|integer']);
            $value = (string) $request->integer('value');

            return [['uz' => $value, 'ru' => $value, 'en' => $value], $value];
        }

        $request->validate([
            'name_uz' => 'required|string|max:255',
            'name_ru' => 'required|string|max:255',
            'name_en' => 'required|string|max:255',
        ]);

        return [
            ['uz' => $request->name_uz, 'ru' => $request->name_ru, 'en' => $request->name_en],
            $request->name_uz,
        ];
    }

    private function redirectBack(Request $request, ?string $success = null, ?string $error = null): RedirectResponse
    {
        $categoryId = $request->input('category_id');
        $redirect = $categoryId
            ? redirect()->route('admin.categories.edit', $categoryId)
            : redirect()->route('admin.parameters.index');

        return $error ? $redirect->with('error', $error) : $redirect->with('success', $success);
    }
}
