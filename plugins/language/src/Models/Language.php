<?php

namespace Botble\Language\Models;

use Eloquent;

/**
 * Botble\Language\Models\Language
 *
 * @mixin \Eloquent
 */
class Language extends Eloquent
{

    /**
     * @var string
     */
    protected $primaryKey = 'lang_id';

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'languages';

    /**
     * @var bool
     */
    public $timestamps = false;

    /**
     * @var array
     */
    protected $fillable = [
        'lang_name',
        'lang_locale',
        'lang_code',
        'lang_is_rtl',
        'lang_flag',
        'lang_order',
    ];
}
