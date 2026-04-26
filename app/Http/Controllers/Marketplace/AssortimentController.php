<?php

namespace App\Http\Controllers\Marketplace;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\CompanyProduct;
use App\Models\Price;
use App\Models\Product;
use Illuminate\Http\Request;

class AssortimentController extends Controller
{
    private function company()
    {
        return auth()->user()->company;
    }

    public function index()
    {
        $company = $this->company();
        $items = $company->companyProducts()
            ->with(['product.translation', 'product.category.translations', 'prices'])
            ->latest()
            ->get();

        return view('marketplace.assortiment.index', compact('company', 'items'));
    }

    public function create()
    {
        $company = $this->company();
        $addedIds = $company->companyProducts()->pluck('product_id')->toArray();

        $categories = Category::with([
            'translations',
            'products' => fn($q) => $q->whereNotIn('id', $addedIds)->with('translation'),
        ])->get()->filter(fn($c) => $c->products->isNotEmpty());

        return view('marketplace.assortiment.create', compact('company', 'categories'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'product_id'      => ['required', 'exists:products,id'],
            'price_retail'    => ['required', 'integer', 'min:1'],
            'price_wholesale' => ['required', 'integer', 'min:1'],
            'currency'        => ['required', 'in:uzs,usd'],
            'is_available'    => ['nullable', 'boolean'],
            'quantity'        => ['nullable', 'integer', 'min:0'],
        ]);

        $company = $this->company();

        if ($company->companyProducts()->where('product_id', $data['product_id'])->exists()) {
            return back()->with('error', 'Bu mahsulot allaqachon assortimentda mavjud.');
        }

        $cp = CompanyProduct::create([
            'company_id'   => $company->id,
            'product_id'   => $data['product_id'],
            'is_available' => $data['is_available'] ?? true,
            'quantity'     => $data['quantity'] ?? null,
        ]);

        Price::create(['company_product_id' => $cp->id, 'type' => 'retail',    'value' => $data['price_retail'],    'currency' => $data['currency']]);
        Price::create(['company_product_id' => $cp->id, 'type' => 'wholesale', 'value' => $data['price_wholesale'], 'currency' => $data['currency']]);

        return redirect()->route('marketplace.assortiment.index')
            ->with('success', 'Mahsulot assortimentga qo\'shildi.');
    }

    public function edit(CompanyProduct $companyProduct)
    {
        $this->authorizeItem($companyProduct);
        $companyProduct->load('product.translation', 'prices');
        return view('marketplace.assortiment.edit', compact('companyProduct'));
    }

    public function update(Request $request, CompanyProduct $companyProduct)
    {
        $this->authorizeItem($companyProduct);

        $data = $request->validate([
            'price_retail'    => ['required', 'integer', 'min:1'],
            'price_wholesale' => ['required', 'integer', 'min:1'],
            'currency'        => ['required', 'in:uzs,usd'],
            'is_available'    => ['nullable', 'boolean'],
            'quantity'        => ['nullable', 'integer', 'min:0'],
        ]);

        $companyProduct->update([
            'is_available' => $data['is_available'] ?? false,
            'quantity'     => $data['quantity'] ?? null,
        ]);

        $companyProduct->prices()->updateOrCreate(['type' => 'retail'],    ['value' => $data['price_retail'],    'currency' => $data['currency']]);
        $companyProduct->prices()->updateOrCreate(['type' => 'wholesale'], ['value' => $data['price_wholesale'], 'currency' => $data['currency']]);

        return redirect()->route('marketplace.assortiment.index')
            ->with('success', 'Narxlar yangilandi.');
    }

    public function destroy(CompanyProduct $companyProduct)
    {
        $this->authorizeItem($companyProduct);
        $companyProduct->prices()->delete();
        $companyProduct->delete();

        return back()->with('success', 'Mahsulot assortimentdan olib tashlandi.');
    }

    private function authorizeItem(CompanyProduct $cp): void
    {
        if ($cp->company_id !== $this->company()->id) {
            abort(403);
        }
    }
}
