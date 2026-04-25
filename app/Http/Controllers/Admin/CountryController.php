<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Country;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CountryController extends Controller
{
    public function index(Request $request): View
    {
        $countries = Country::with('translations')
            ->when($request->search, fn($q) =>
                $q->whereHas('translations', fn($t) =>
                    $t->where('name', 'like', "%{$request->search}%")
                )
            )
            ->latest()
            ->paginate(20)
            ->withQueryString();

        return view('admin.countries.index', compact('countries'));
    }

    public function create(): View
    {
        return view('admin.countries.form');
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'code'    => 'nullable|string|max:2|alpha',
            'name_uz' => 'required|string|max:255',
            'name_ru' => 'required|string|max:255',
        ]);

        $country = Country::create(['code' => $request->code]);

        foreach (['uz', 'ru'] as $lang) {
            $country->translations()->create([
                'lang' => $lang,
                'name' => $request->{"name_{$lang}"},
            ]);
        }

        return redirect()->route('admin.countries.index')
            ->with('success', "«{$request->name_uz}» davlati qo'shildi");
    }

    public function edit(Country $country): View
    {
        $country->load('translations');
        return view('admin.countries.form', compact('country'));
    }

    public function update(Request $request, Country $country): RedirectResponse
    {
        $request->validate([
            'code'    => 'nullable|string|max:2|alpha',
            'name_uz' => 'required|string|max:255',
            'name_ru' => 'required|string|max:255',
        ]);

        $country->update(['code' => $request->code]);

        foreach (['uz', 'ru'] as $lang) {
            $country->translations()->updateOrCreate(
                ['lang' => $lang],
                ['name' => $request->{"name_{$lang}"}]
            );
        }

        return redirect()->route('admin.countries.index')
            ->with('success', "«{$request->name_uz}» yangilandi");
    }

    public function destroy(Country $country): RedirectResponse
    {
        $name = $country->translations->where('lang', 'uz')->first()?->name ?? $country->id;
        $country->delete();

        return redirect()->route('admin.countries.index')
            ->with('success', "«{$name}» o'chirildi");
    }
}
