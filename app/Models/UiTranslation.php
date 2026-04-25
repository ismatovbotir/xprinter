<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int    $id
 * @property string $group
 * @property string $key
 * @property string $lang
 * @property string $value
 */
class UiTranslation extends Model
{
    protected $fillable = ['group', 'key', 'lang', 'value'];
}
