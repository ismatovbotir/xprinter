<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\Region;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\View\View;

/**
 * Public directory of approved dealers, partners and service centers.
 * Shows only business-facing data — never INN, legal form, VAT status or prices.
 */
class PartnerDirectoryController extends Controller
{
    public const TYPES = ['retail', 'partner', 'service'];

    public function index(Request $request): View
    {
        $type     = in_array($request->query('type'), self::TYPES, true) ? $request->query('type') : null;
        $regionId = $request->integer('region') ?: null;
        $search   = trim((string) $request->query('search', ''));

        $partners = self::publicQuery()
            ->when($type, fn ($q) => $q->whereJsonContains('types', $type))
            ->when($regionId, fn ($q) => $q->whereHas('addresses.city', fn ($c) => $c->where('region_id', $regionId)))
            ->when($search !== '', fn ($q) => $q->where(fn ($w) => $w
                ->where('brand', 'like', "%{$search}%")
                ->orWhere('name', 'like', "%{$search}%")))
            ->paginate(18)
            ->withQueryString();

        // Only regions that actually have an approved partner.
        $regions = Region::with('translations')
            ->whereHas('cities.addresses.company', fn ($q) => $q->where('status', 'approved'))
            ->get();

        return view('partners.index', compact('partners', 'regions', 'type', 'regionId', 'search'));
    }

    public function show(string $slug): View
    {
        $partner = self::publicQuery()
            ->with([
                'contacts',
                'companyProducts' => fn ($q) => $q->where('is_available', true)
                    ->with('product.translations', 'product.category.translations'),
            ])
            ->where('slug', $slug)
            ->firstOrFail();

        return view('partners.show', compact('partner'));
    }

    /** Approved companies with the relations every public card needs, authorized ones first. */
    public static function publicQuery(): Builder
    {
        return Company::query()
            ->where('status', 'approved')
            ->select(['id', 'name', 'brand', 'slug', 'logo', 'phone', 'types', 'manufacturer_status'])
            ->with(['addresses.city.translations', 'addresses.city.region.translations'])
            ->withCount(['companyProducts as products_count' => fn ($q) => $q->where('is_available', true)])
            ->orderByRaw("CASE manufacturer_status WHEN 'authorized_distributor' THEN 0 WHEN 'authorized_partner' THEN 1 ELSE 2 END")
            ->orderByRaw('COALESCE(brand, name)');
    }
}
