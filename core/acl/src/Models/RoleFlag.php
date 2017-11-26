<?php

namespace Botble\ACL\Models;

use Eloquent;

class RoleFlag extends Eloquent
{

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'role_flags';

    /**
     * @var array
     */
    protected $primaryKey = 'role_id';

    /**
     * Disable automatic handling of timestamps.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * @var array
     */
    protected $fillable = ['role_id', 'flag_id'];
}
