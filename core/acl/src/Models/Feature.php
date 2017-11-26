<?php

namespace Botble\ACL\Models;

use Eloquent;

class Feature extends Eloquent
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'features';

    /**
     * @var string
     */
    protected $primaryKey = 'feature_id';

    /**
     * The date fields for the model.clear
     *
     * @var array
     */
    protected $dates = ['created_at', 'updated_at'];

    /**
     * @var array
     */
    protected $fillable = ['feature_id'];
}
