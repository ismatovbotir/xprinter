<?php

namespace App\Http\Controllers\Producer;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\Product;
use App\Models\ProductSerial;

class DashboardController extends Controller
{
    public function __invoke()
    {
        $dealerCount     = Company::where('status', 'approved')->count();
        $partnerCount    = Company::whereIn('manufacturer_status', ['authorized_partner', 'authorized_distributor'])->count();
        $pendingCount    = Company::where('status', 'pending')->count();

        $serialTotal     = ProductSerial::count();
        $serialAvailable = ProductSerial::where('status', 'available')->count();
        $serialSold      = ProductSerial::where('status', 'sold')->count();
        $serialRegistered= ProductSerial::where('status', 'registered')->count();

        $productCount    = Product::count();

        return view('producer.dashboard', compact(
            'dealerCount', 'partnerCount', 'pendingCount',
            'serialTotal', 'serialAvailable', 'serialSold', 'serialRegistered',
            'productCount'
        ));
    }
}
