<?php

namespace Botble\Gallery\Models;

use Botble\ACL\Models\User;
use Eloquent;
use Illuminate\Database\Eloquent\SoftDeletes;

class Gallery extends Eloquent
{
    use SoftDeletes;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'galleries';

    protected $primaryKey = 'id';

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = ['deleted_at'];

    /**
     * The date fields for the model.clear
     *
     * @var array
     */
    protected $dates = ['created_at', 'updated_at', 'deleted_at'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'description', 'parent_id', 'slug', 'featured', 'order', 'image', 'status'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     * @author Sang Nguyen
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
