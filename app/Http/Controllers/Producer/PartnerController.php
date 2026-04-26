<?php

namespace App\Http\Controllers\Producer;

use App\Http\Controllers\Controller;
use App\Models\Company;
use Illuminate\Http\Request;

class PartnerController extends Controller
{
    public function index()
    {
        $partners = Company::where('status', 'approved')
            ->select('id', 'brand', 'manufacturer_status', 'types', 'created_at')
            ->withCount('users')
            ->orderByRaw("FIELD(manufacturer_status, 'authorized_distributor', 'authorized_partner', 'none')")
            ->get();

        return view('producer.partners.index', compact('partners'));
    }

    public function updateStatus(Request $request, Company $company)
    {
        $data = $request->validate([
            'manufacturer_status' => ['required', 'in:none,authorized_partner,authorized_distributor'],
        ]);

        $company->update($data);

        return back()->with('success', 'Hamkorlik statusi yangilandi.');
    }
}
