<?php

namespace Botble\AuditLog\Models;

use Botble\ACL\Models\User;
use Eloquent;

class AuditHistory extends Eloquent
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'audit_history';

    /**
     * The date fields for the model.clear
     *
     * @var array
     */
    protected $dates = [
        'created_at',
        'updated_at',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     * @author Sang Nguyen
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
