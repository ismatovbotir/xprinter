<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Company;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class CompanyController extends Controller
{
    public function index(): View
    {
        $companies = Company::latest()->paginate(20);
        return view('admin.companies.index', compact('companies'));
    }

    public function pending(): View
    {
        $companies = Company::where('status', 'pending')->latest()->get();
        return view('admin.companies.pending', compact('companies'));
    }

    public function create(): View
    {
        return view('admin.companies.create');
    }

    public function store(): RedirectResponse
    {
        return redirect()->route('admin.companies.index')
            ->with('success', 'Kompaniya yaratildi');
    }

    public function show(Company $company): View
    {
        return view('admin.companies.show', compact('company'));
    }

    public function edit(Company $company): View
    {
        return view('admin.companies.edit', compact('company'));
    }

    public function update(Company $company): RedirectResponse
    {
        return redirect()->route('admin.companies.index')
            ->with('success', 'Kompaniya yangilandi');
    }

    public function destroy(Company $company): RedirectResponse
    {
        $company->delete();
        return redirect()->route('admin.companies.index')
            ->with('success', 'Kompaniya o\'chirildi');
    }

    public function approve(Company $company): RedirectResponse
    {
        $company->update(['status' => 'approved']);
        return redirect()->back()->with('success', "{$company->name} tasdiqlandi");
    }

    public function reject(Company $company): RedirectResponse
    {
        $company->update(['status' => 'rejected']);
        return redirect()->back()->with('success', "{$company->name} rad etildi");
    }
}
