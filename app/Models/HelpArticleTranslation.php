<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class HelpArticleTranslation extends Model
{
    protected $table = 'help_article_translations';

    protected $fillable = [
        'help_article_id', 'lang', 'title', 'content'
    ];

    public function helpArticle(): BelongsTo
    {
        return $this->belongsTo(HelpArticle::class);
    }
}
