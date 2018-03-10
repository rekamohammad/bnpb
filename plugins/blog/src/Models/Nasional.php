<?php

namespace Botble\Blog\Models;

use Botble\ACL\Models\User;
use Eloquent;
use Illuminate\Database\Eloquent\SoftDeletes;
use Venturecraft\Revisionable\RevisionableTrait;

class Nasional extends Eloquent
{
    use RevisionableTrait;
    use SoftDeletes;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'links';

    /**
     * @var mixed
     */
    protected $revisionEnabled = true;

    /**
     * @var mixed
     */
    protected $revisionCleanup = true;

    /**
     * @var int
     */
    protected $historyLimit = 20;

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
    protected $fillable = [
        'categories',
		'name',
		'url',
		'status',
    ]; 
}
