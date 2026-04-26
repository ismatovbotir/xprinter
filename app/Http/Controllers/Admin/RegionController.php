<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Country;
use App\Models\Region;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class RegionController extends Controller
{
    public function index(Request $request): View
    {
        $regions = Region::with(['translations', 'country.translations'])
            ->when($request->search, fn($q) =>
                $q->whereHas('translations', fn($t) =>
                    $t->where('name', 'like', "%{$request->search}%")
                )
            )
            ->when($request->country_id, fn($q) =>
                $q->where('country_id', $request->country_id)
            )
            ->latest()
            ->paginate(20)
            ->withQueryString();

        $countries = Country::with('translations')->get();

        return view('admin.regions.index', compact('regions', 'countries'));
    }

    public function create(): View
    {
        $countries = Country::with('translations')->get();
        return view('admin.regions.form', compact('countries'));
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'country_id' => 'required|exists:countries,id',
            'name_uz'    => 'required|string|max:255',
            'name_ru'    => 'required|string|max:255',
        ]);

        $region = Region::create(['country_id' => $request->country_id]);

        foreach (['uz', 'ru', 'en'] as $lang) {
            $region->translations()->create([
                'lang' => $lang,
                'name' => $request->{"name_{$lang}"},
            ]);
        }

        return redirect()->route('admin.regions.index')
            ->with('success', "«{$request->name_uz}» viloyati qo'shildi");
    }

    public function edit(Region $region): View
    {
        $region->load('translations');
        $countries = Country::with('translations')->get();
        return view('admin.regions.form', compact('region', 'countries'));
    }

    public function update(Request $request, Region $region): RedirectResponse
    {
        $request->validate([
            'country_id' => 'required|exists:countries,id',
            'name_uz'    => 'required|string|max:255',
            'name_ru'    => 'required|string|max:255',
        ]);

        $region->update(['country_id' => $request->country_id]);

        foreach (['uz', 'ru', 'en'] as $lang) {
            $region->translations()->updateOrCreate(
                ['lang' => $lang],
                ['name' => $request->{"name_{$lang}"}]
            );
        }

        return redirect()->route('admin.regions.index')
            ->with('success', "«{$request->name_uz}» yangilandi");
    }

    public function destroy(Region $region): RedirectResponse
    {
        $name = $region->translations->where('lang', 'uz')->first()?->name ?? $region->id;
        $region->delete();

        return redirect()->route('admin.regions.index')
            ->with('success', "«{$name}» o'chirildi");
    }
}
