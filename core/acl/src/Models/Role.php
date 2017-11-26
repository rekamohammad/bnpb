<?php

namespace Botble\ACL\Models;

use Cartalyst\Sentinel\Roles\EloquentRole;
use Illuminate\Database\Eloquent\SoftDeletes;

class Role extends EloquentRole
{

    use SoftDeletes;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'roles';

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
        'name',
        'slug',
        'description',
        'is_staff',
        'is_default',
        'created_by',
        'updated_by',
    ];

    /**
     * Returns the list of flags that belong to this role
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     * @author Sang Nguyen
     */
    public function flags()
    {
        return $this->belongsToMany(PermissionFlag::class, 'role_flags', 'role_id', 'flag_id');
    }

    /**
     * @return mixed
     * @author Sang Nguyen
     */
    public function users()
    {
        return $this->belongsToMany(User::class, 'role_users', 'role_id', 'user_id');
    }
}
