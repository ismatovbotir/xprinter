<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Company;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CompanyController extends Controller
{
    public function index(Request $request): View
    {
        $companies = Company::with('users')
            ->when($request->search, fn($q) =>
                $q->where('name', 'like', "%{$request->search}%")
                  ->orWhere('brand', 'like', "%{$request->search}%")
                  ->orWhere('inn', 'like', "%{$request->search}%")
            )
            ->when($request->status, fn($q) =>
                $q->where('status', $request->status)
            )
            ->latest()
            ->paginate(20)
            ->withQueryString();

        return view('admin.companies.index', compact('companies'));
    }

    public function pending(): View
    {
        $companies = Company::with('users')
            ->where('status', 'pending')
            ->latest()
            ->get();

        return view('admin.companies.pending', compact('companies'));
    }

    public function show(Company $company): View
    {
        $company->load('users', 'addresses', 'contacts');
        return view('admin.companies.show', compact('company'));
    }

    public function edit(Company $company): View
    {
        return view('admin.companies.edit', compact('company'));
    }

    public function update(Request $request, Company $company): RedirectResponse
    {
        $request->validate([
            'name'                => 'required|string|max:255',
            'brand'               => 'nullable|string|max:255',
            'inn'                 => 'nullable|string|max:20',
            'phone'               => 'nullable|string|max:30',
            'legal_form'          => 'nullable|string|max:100',
            'status'              => 'required|in:pending,approved,rejected',
            'vat_status'          => 'required|in:non_payer,payer',
            'manufacturer_status' => 'required|in:none,authorized_partner,authorized_distributor',
        ]);

        $company->update($request->only([
            'name', 'brand', 'inn', 'phone', 'legal_form',
            'status', 'vat_status', 'manufacturer_status',
        ]));

        return redirect()->route('admin.companies.show', $company)
            ->with('success', "«{$company->name}» yangilandi");
    }

    public function destroy(Company $company): RedirectResponse
    {
        $name = $company->name;
        $company->delete();

        return redirect()->route('admin.companies.index')
            ->with('success', "«{$name}» o'chirildi");
    }

    public function approve(Request $request, Company $company): RedirectResponse
    {
        $company->update([
            'status'     => 'approved',
            'admin_note' => $request->input('admin_note') ?: null,
        ]);
        return redirect()->back()->with('success', "«{$company->name}» tasdiqlandi");
    }

    public function reject(Request $request, Company $company): RedirectResponse
    {
        $request->validate([
            'admin_note' => ['required', 'string', 'max:1000'],
        ]);

        $company->update([
            'status'     => 'rejected',
            'admin_note' => $request->input('admin_note'),
        ]);
        return redirect()->back()->with('success', "«{$company->name}» rad etildi");
    }
}
