<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * Knowledge-base article: troubleshooting, settings, driver / tool explainers, glossary, FAQ.
 *
 * @property int         $id
 * @property string      $slug
 * @property string      $type
 * @property int|null    $category_id
 * @property string      $difficulty
 * @property string|null $keywords
 * @property bool        $is_published
 * @property int         $sort_order
 * @property int         $views_count
 */
class KbArticle extends Model
{
    protected $table = 'kb_articles';

    /** type => [icon key, admin label (uz)] */
    public const TYPES = [
        'troubleshooting' => 'Muammoni hal qilish',
        'settings'        => 'Sozlamalar',
        'driver'          => 'Drayverlar',
        'tool'            => 'Utilitalar (tools)',
        'glossary'        => 'Lug\'at (atamalar)',
        'faq'             => 'FAQ',
    ];

    public const DIFFICULTIES = ['easy', 'medium', 'pro'];

    protected $fillable = [
        'slug', 'type', 'category_id', 'difficulty', 'keywords', 'is_published', 'sort_order', 'views_count',
    ];

    protected $casts = [
        'is_published' => 'boolean',
    ];

    // ── Relations ───────────────────────────────────────────

    public function translations(): HasMany
    {
        return $this->hasMany(KbArticleTranslation::class);
    }

    public function translation(): HasOne
    {
        return $this->hasOne(KbArticleTranslation::class)->where('lang', app()->getLocale());
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'kb_article_product');
    }

    public function files(): BelongsToMany
    {
        return $this->belongsToMany(File::class, 'kb_article_file');
    }

    // ── Scopes ──────────────────────────────────────────────

    public function scopePublished(Builder $q): Builder
    {
        return $q->where('is_published', true);
    }

    public function scopeOfType(Builder $q, string $type): Builder
    {
        return $q->where('type', $type);
    }

    /** Articles that apply to a product directly or via its category. */
    public function scopeForProduct(Builder $q, Product $product): Builder
    {
        return $q->where(function (Builder $w) use ($product) {
            $w->whereHas('products', fn ($p) => $p->where('products.id', $product->id))
              ->orWhere('category_id', $product->category_id);
        });
    }

    /** Full-text-ish search over the current-locale translation and keywords. */
    public function scopeSearch(Builder $q, string $term): Builder
    {
        $like = '%' . str_replace(['%', '_'], ['\%', '\_'], trim($term)) . '%';

        return $q->where(function (Builder $w) use ($like) {
            $w->where('keywords', 'like', $like)
              ->orWhereHas('translations', fn ($t) => $t->where('title', 'like', $like)
                  ->orWhere('excerpt', 'like', $like)
                  ->orWhere('body', 'like', $like));
        });
    }

    // ── Helpers ─────────────────────────────────────────────

    /** Translation in the current locale, falling back to uz, then anything. */
    public function t(?string $lang = null): ?KbArticleTranslation
    {
        $lang ??= app()->getLocale();

        return $this->translations->firstWhere('lang', $lang)
            ?? $this->translations->firstWhere('lang', 'uz')
            ?? $this->translations->first();
    }

    public function url(): string
    {
        return route('support.show', $this->slug);
    }

    public function incrementViews(): void
    {
        // Silent counter: no updated_at bump, no activity log noise.
        static::withoutEvents(fn () => $this->newQuery()->whereKey($this->id)->increment('views_count'));
    }
}
