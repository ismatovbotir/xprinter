<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ChatbotQuestion extends Model
{
    protected $fillable = ['user_id', 'channel', 'lang', 'question', 'answer', 'status'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
