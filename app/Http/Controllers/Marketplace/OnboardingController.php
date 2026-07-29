<?php

namespace App\Http\Controllers\Marketplace;

use App\Http\Controllers\Controller;
use App\Models\Address;
use App\Models\City;
use App\Models\Company;
use App\Models\Country;
use App\Models\Region;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class OnboardingController extends Controller
{
    public function show()
    {
        $user = auth()->user();

        if ($user->company) {
            return redirect()->route('marketplace.pending');
        }

        $countries = Country::with('translations')->get();
        $regions   = Region::with('translations')->get();
        $cities    = City::with('translations')->get();

        return view('marketplace.onboarding', compact('countries', 'regions', 'cities'));
    }

    public function store(Request $request)
    {
        $user = auth()->user();

        if ($user->company) {
            return redirect()->route('marketplace.pending');
        }

        $data = $request->validate([
            'brand'   => ['required', 'string', 'max:255'],
            'inn'     => ['required', 'string', 'max:20', 'unique:companies,inn'],
            'phone'   => ['required', 'string', 'max:20'],
            'types'   => ['required', 'array', 'min:1'],
            'types.*' => ['in:retail,partner,service'],
            'city_id' => ['required', 'exists:cities,id'],
        ]);

        $company = Company::create([
            'brand'               => $data['brand'],
            'inn'                 => $data['inn'],
            'phone'               => $data['phone'],
            'types'               => $data['types'],
            'status'              => 'pending',
            'vat_status'          => 'non_payer',
            'manufacturer_status' => 'none',
            'name'                => $data['brand'],
            'slug'                => $this->uniqueSlug($data['brand']),
        ]);

        $user->update(['company_id' => $company->id]);

        Address::create([
            'company_id' => $company->id,
            'city_id'    => $data['city_id'],
            'name'       => 'Asosiy manzil',
        ]);

        return redirect()->route('marketplace.pending');
    }

    private function uniqueSlug(string $brand): string
    {
        $base = Str::slug($brand) ?: 'company';
        $slug = $base;
        $i    = 2;

        while (Company::where('slug', $slug)->exists()) {
            $slug = "{$base}-{$i}";
            $i++;
        }

        return $slug;
    }
}
