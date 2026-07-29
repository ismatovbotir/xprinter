<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class HelpArticle extends Model
{
    protected $table = 'help_articles';

    protected $fillable = [
        'slug', 'section', 'placement', 'is_active', 'sort_order'
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function translations(): HasMany
    {
        return $this->hasMany(HelpArticleTranslation::class);
    }

    public function translation(): HasOne
    {
        return $this->hasOne(HelpArticleTranslation::class)->where('lang', app()->getLocale());
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true)->orderBy('sort_order');
    }
}
