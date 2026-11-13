<?php

namespace App\Http\Controllers;

use App\Models\KbArticle;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\View\View;

/**
 * Public knowledge base: /support — how to fix, how to configure, what a driver / tool is.
 */
class SupportController extends Controller
{
    public function index(Request $request): View
    {
        $q = trim((string) $request->query('q', ''));

        $results = null;
        if ($q !== '') {
            $results = KbArticle::published()
                ->with(['translations', 'products'])
                ->search($q)
                ->orderByDesc('views_count')
                ->limit(30)
                ->get();
        }

        $counts = KbArticle::published()
            ->selectRaw('type, count(*) as n')
            ->groupBy('type')
            ->pluck('n', 'type');

        $popular = KbArticle::published()
            ->with('translations')
            ->whereIn('type', ['troubleshooting', 'settings'])
            ->orderByDesc('views_count')
            ->orderBy('sort_order')
            ->limit(6)
            ->get();

        $glossary = KbArticle::published()
            ->with('translations')
            ->ofType('glossary')
            ->orderBy('sort_order')
            ->limit(8)
            ->get();

        return view('support.index', [
            'q'        => $q,
            'results'  => $results,
            'counts'   => $counts,
            'popular'  => $popular,
            'glossary' => $glossary,
            'types'    => array_keys(KbArticle::TYPES),
        ]);
    }

    public function type(Request $request, string $type): View
    {
        abort_unless(array_key_exists($type, KbArticle::TYPES), 404);

        $product = $request->filled('product')
            ? Product::with('translations')->where('slug', $request->product)->first()
            : null;

        $articles = KbArticle::published()
            ->with(['translations', 'products'])
            ->ofType($type)
            ->when($product, fn ($q) => $q->forProduct($product))
            ->orderBy('sort_order')
            ->orderByDesc('views_count')
            ->paginate(20)
            ->withQueryString();

        return view('support.type', compact('type', 'articles', 'product'));
    }

    public function show(string $slug): View
    {
        $article = KbArticle::published()
            ->with(['translations', 'products.translations', 'products.category', 'files', 'category.translations'])
            ->where('slug', $slug)
            ->firstOrFail();

        $article->incrementViews();

        $related = KbArticle::published()
            ->with('translations')
            ->where('id', '!=', $article->id)
            ->where(function ($q) use ($article) {
                $q->where('type', $article->type);
                if ($article->category_id) {
                    $q->orWhere('category_id', $article->category_id);
                }
            })
            ->orderByDesc('views_count')
            ->limit(4)
            ->get();

        // "Didn't help?" → nearest verified service centers.
        $serviceCenters = PartnerDirectoryController::publicQuery()
            ->whereJsonContains('types', 'service')
            ->limit(3)
            ->get();

        return view('support.show', compact('article', 'related', 'serviceCenters'));
    }
}
