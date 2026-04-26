<?php

namespace App\Http\Controllers\Producer;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductSerial;
use Illuminate\Http\Request;

class SerialController extends Controller
{
    public function index(Request $request)
    {
        $query = ProductSerial::with('product.translation')
            ->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('product_id')) {
            $query->where('product_id', $request->product_id);
        }

        $serials  = $query->paginate(50)->withQueryString();
        $products = Product::with('translation')->orderBy('model_number')->get();

        return view('producer.serials.index', compact('serials', 'products'));
    }

    public function import()
    {
        $products = Product::with('translation')->orderBy('model_number')->get();
        return view('producer.serials.import', compact('products'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'product_id' => ['required', 'exists:products,id'],
            'csv'        => ['required', 'file', 'mimes:csv,txt', 'max:2048'],
        ]);

        $file    = $request->file('csv');
        $lines   = array_filter(array_map('trim', file($file->getRealPath())));
        $created = 0;
        $skipped = 0;

        foreach ($lines as $line) {
            $serial = trim(str_getcsv($line)[0] ?? '');
            if (!$serial) continue;

            if (ProductSerial::where('serial_number', $serial)->exists()) {
                $skipped++;
                continue;
            }

            ProductSerial::create([
                'product_id'    => $data['product_id'],
                'serial_number' => $serial,
                'status'        => 'available',
            ]);
            $created++;
        }

        return redirect()->route('producer.serials.index')
            ->with('success', "Import yakunlandi: {$created} ta qo'shildi, {$skipped} ta o'tkazib yuborildi.");
    }
}
