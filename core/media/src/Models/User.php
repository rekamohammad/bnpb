<?php

namespace Botble\Media\Models;

use Eloquent;

class User extends Eloquent
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'users';

    /**
     * @var array
     */
    protected $hidden = ['password'];
}