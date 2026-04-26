<?php

namespace App\Http\Controllers\Marketplace;

use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
    public function __invoke()
    {
        $user    = auth()->user();
        $company = $user->company()->with(['users', 'companyProducts'])->first();

        return view('marketplace.dashboard', compact('user', 'company'));
    }
}
