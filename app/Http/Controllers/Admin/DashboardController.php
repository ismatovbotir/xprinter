<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\Product;
use App\Models\User;

class DashboardController extends Controller
{
    public function __invoke()
    {
        $stats = [
            'companies_total'   => Company::count(),
            'companies_pending' => Company::where('status', 'pending')->count(),
            'products_total'    => Product::count(),
            'users_total'       => User::whereIn('role', ['owner', 'user'])->count(),
        ];

        $pending = Company::where('status', 'pending')->latest()->get();

        $companies = Company::where('status', 'approved')->latest()->take(10)->get();

        return view('admin.dashboard', compact('stats', 'pending', 'companies'));
    }
}
