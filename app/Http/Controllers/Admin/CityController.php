<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\City;
use App\Models\Country;
use App\Models\Region;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CityController extends Controller
{
    public function index(Request $request): View
    {
        $cities = City::with(['translations', 'region.translations', 'region.country.translations'])
            ->when($request->search, fn($q) =>
                $q->whereHas('translations', fn($t) =>
                    $t->where('name', 'like', "%{$request->search}%")
                )
            )
            ->when($request->region_id, fn($q) =>
                $q->where('region_id', $request->region_id)
            )
            ->latest()
            ->paginate(20)
            ->withQueryString();

        $regions = Region::with(['translations', 'country.translations'])->get();

        return view('admin.cities.index', compact('cities', 'regions'));
    }

    public function create(): View
    {
        $countries = Country::with('translations')->get();
        $regions   = Region::with('translations')->get();
        return view('admin.cities.form', compact('countries', 'regions'));
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'region_id' => 'required|exists:regions,id',
            'name_uz'   => 'required|string|max:255',
            'name_ru'   => 'required|string|max:255',
        ]);

        $city = City::create(['region_id' => $request->region_id]);

        foreach (['uz', 'ru', 'en'] as $lang) {
            $city->translations()->create([
                'lang' => $lang,
                'name' => $request->{"name_{$lang}"},
            ]);
        }

        return redirect()->route('admin.cities.index')
            ->with('success', "«{$request->name_uz}» shahri qo'shildi");
    }

    public function edit(City $city): View
    {
        $city->load('translations', 'region');
        $countries = Country::with('translations')->get();
        $regions   = Region::with('translations')
            ->where('country_id', $city->region->country_id)
            ->get();

        return view('admin.cities.form', compact('city', 'countries', 'regions'));
    }

    public function update(Request $request, City $city): RedirectResponse
    {
        $request->validate([
            'region_id' => 'required|exists:regions,id',
            'name_uz'   => 'required|string|max:255',
            'name_ru'   => 'required|string|max:255',
        ]);

        $city->update(['region_id' => $request->region_id]);

        foreach (['uz', 'ru', 'en'] as $lang) {
            $city->translations()->updateOrCreate(
                ['lang' => $lang],
                ['name' => $request->{"name_{$lang}"}]
            );
        }

        return redirect()->route('admin.cities.index')
            ->with('success', "«{$request->name_uz}» yangilandi");
    }

    public function destroy(City $city): RedirectResponse
    {
        $name = $city->translations->where('lang', 'uz')->first()?->name ?? $city->id;
        $city->delete();

        return redirect()->route('admin.cities.index')
            ->with('success', "«{$name}» o'chirildi");
    }
}
