<?php

namespace Botble\Translation\Models;

use Eloquent;

/**
 * Translation model
 *
 * @property integer $id
 * @property integer $status
 * @property string $locale
 * @property string $group
 * @property string $key
 * @property string $value
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 */
class Translation extends Eloquent
{

    const STATUS_SAVED = 0;
    const STATUS_CHANGED = 1;

    protected $table = 'translations';
    protected $guarded = [
        'id',
        'created_at',
        'updated_at',
    ];
}
