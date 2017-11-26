<?php

namespace Botble\ACL\Models;

use Eloquent;

class Invite extends Eloquent
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'invites';

    /**
     * The date fields for the model.clear
     *
     * @var array
     */
    protected $dates = ['created_at', 'updated_at'];

    /**
     * @var array
     */
    protected $fillable = [
        'token',
        'invitee_id',
        'user_id',
        'role_id',
    ];
}
