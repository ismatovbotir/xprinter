<?php

namespace App\Http\Controllers\Marketplace;

use App\Http\Controllers\Controller;
use App\Models\Address;
use App\Models\City;
use App\Models\Region;
use Illuminate\Http\Request;

class CompanyController extends Controller
{
    private function company()
    {
        return auth()->user()->company;
    }

    public function show()
    {
        $company = $this->company()->load('addresses.city.region', 'addresses.city.translations');
        $regions = Region::with('translations', 'cities.translations')->get();
        return view('marketplace.company.show', compact('company', 'regions'));
    }

    public function update(Request $request)
    {
        $data = $request->validate([
            'brand' => ['nullable', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:30'],
            'types' => ['nullable', 'array'],
            'types.*' => ['in:retail,partner,service'],
        ]);

        $this->company()->update($data);

        return back()->with('success', 'Kompaniya ma\'lumotlari yangilandi.');
    }

    public function storeAddress(Request $request)
    {
        $data = $request->validate([
            'city_id'     => ['required', 'exists:cities,id'],
            'name'        => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:500'],
            'postal_code' => ['nullable', 'string', 'max:20'],
        ]);

        $company = $this->company();
        $data['company_id'] = $company->id;
        $data['user_id']    = auth()->id();

        Address::create($data);

        return back()->with('success', 'Manzil qo\'shildi.');
    }

    public function destroyAddress(Address $address)
    {
        if ($address->company_id !== $this->company()->id) {
            abort(403);
        }

        $address->delete();

        return back()->with('success', 'Manzil o\'chirildi.');
    }
}
