<?php

namespace App\Http\Controllers\Marketplace;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\CompanyProduct;
use App\Models\CompanyProductParameterValue;
use App\Models\Price;
use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AssortimentController extends Controller
{
    private function company()
    {
        return auth()->user()->company;
    }

    public function index(): View
    {
        $company = $this->company();
        $items = $company->companyProducts()
            ->with([
                'product.translation',
                'product.category.translations',
                'prices',
                'variantValues.parameter.translations',
                'variantValues.parameterValue.translations',
            ])
            ->latest()
            ->get();

        return view('marketplace.assortiment.index', compact('company', 'items'));
    }

    public function create(): View
    {
        $company = $this->company();
        $addedIds = $company->companyProducts()->pluck('product_id')->toArray();

        $categories = Category::with([
            'translations',
            'products' => fn($q) => $q->whereNotIn('id', $addedIds)->with('translation'),
            'parameters.translations',
            'parameters.values.translations',
        ])->get()->filter(fn($c) => $c->products->isNotEmpty());

        return view('marketplace.assortiment.create', compact('company', 'categories'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'product_id'        => ['required', 'exists:products,id'],
            'price_retail'      => ['required', 'integer', 'min:1'],
            'price_wholesale'   => ['required', 'integer', 'min:1'],
            'currency'          => ['required', 'in:uzs,usd'],
            'is_available'      => ['nullable', 'boolean'],
            'quantity'          => ['nullable', 'integer', 'min:0'],
            'variant_values'    => ['nullable', 'array'],
            'variant_values.*'  => ['nullable', 'integer', 'exists:parameter_values,id'],
        ]);

        $company = $this->company();

        if ($company->companyProducts()->where('product_id', $data['product_id'])->exists()) {
            return back()->with('error', 'Bu mahsulot allaqachon assortimentda mavjud.');
        }

        $product = Product::with('category.parameters')->findOrFail($data['product_id']);
        $variantParams = $product->category->parameters->filter(fn($p) => $p->pivot->is_variant);

        foreach ($variantParams as $param) {
            if (empty($data['variant_values'][$param->id])) {
                return back()->withErrors([
                    "variant_values.{$param->id}" => 'Qiymatni tanlang',
                ])->withInput();
            }
        }

        $cp = CompanyProduct::create([
            'company_id'   => $company->id,
            'product_id'   => $data['product_id'],
            'is_available' => $data['is_available'] ?? true,
            'quantity'     => $data['quantity'] ?? null,
        ]);

        Price::create(['company_product_id' => $cp->id, 'type' => 'retail',    'value' => $data['price_retail'],    'currency' => $data['currency']]);
        Price::create(['company_product_id' => $cp->id, 'type' => 'wholesale', 'value' => $data['price_wholesale'], 'currency' => $data['currency']]);

        foreach ($variantParams as $param) {
            CompanyProductParameterValue::create([
                'company_product_id' => $cp->id,
                'parameter_id'       => $param->id,
                'parameter_value_id' => $data['variant_values'][$param->id],
            ]);
        }

        return redirect()->route('marketplace.assortiment.index')
            ->with('success', 'Mahsulot assortimentga qo\'shildi.');
    }

    public function edit(CompanyProduct $companyProduct): View
    {
        $this->authorizeItem($companyProduct);
        $companyProduct->load([
            'product.translation',
            'product.category.parameters.translations',
            'product.category.parameters.values.translations',
            'prices',
            'variantValues',
        ]);

        return view('marketplace.assortiment.edit', compact('companyProduct'));
    }

    public function update(Request $request, CompanyProduct $companyProduct): RedirectResponse
    {
        $this->authorizeItem($companyProduct);

        $data = $request->validate([
            'price_retail'      => ['required', 'integer', 'min:1'],
            'price_wholesale'   => ['required', 'integer', 'min:1'],
            'currency'          => ['required', 'in:uzs,usd'],
            'is_available'      => ['nullable', 'boolean'],
            'quantity'          => ['nullable', 'integer', 'min:0'],
            'variant_values'    => ['nullable', 'array'],
            'variant_values.*'  => ['nullable', 'integer', 'exists:parameter_values,id'],
        ]);

        $companyProduct->loadMissing('product.category.parameters');
        $variantParams = $companyProduct->product->category->parameters->filter(fn($p) => $p->pivot->is_variant);

        foreach ($variantParams as $param) {
            if (empty($data['variant_values'][$param->id])) {
                return back()->withErrors([
                    "variant_values.{$param->id}" => 'Qiymatni tanlang',
                ])->withInput();
            }
        }

        $companyProduct->update([
            'is_available' => $data['is_available'] ?? false,
            'quantity'     => $data['quantity'] ?? null,
        ]);

        $companyProduct->prices()->updateOrCreate(['type' => 'retail'],    ['value' => $data['price_retail'],    'currency' => $data['currency']]);
        $companyProduct->prices()->updateOrCreate(['type' => 'wholesale'], ['value' => $data['price_wholesale'], 'currency' => $data['currency']]);

        foreach ($variantParams as $param) {
            CompanyProductParameterValue::updateOrCreate(
                ['company_product_id' => $companyProduct->id, 'parameter_id' => $param->id],
                ['parameter_value_id' => $data['variant_values'][$param->id]]
            );
        }

        return redirect()->route('marketplace.assortiment.index')
            ->with('success', 'Narxlar yangilandi.');
    }

    public function destroy(CompanyProduct $companyProduct): RedirectResponse
    {
        $this->authorizeItem($companyProduct);
        $companyProduct->prices()->delete();
        $companyProduct->variantValues()->delete();
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
