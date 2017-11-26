<?php

namespace Botble\Blog\Models;

use Eloquent;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Eloquent
{
    use SoftDeletes;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'categories';

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
    protected $fillable = [
        'name',
        'description',
        'parent_id',
        'slug',
        'icon',
        'featured',
        'order',
        'is_default',
        'status',
        'user_id',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     * @author Sang Nguyen
     */
    public function posts()
    {
        return $this->belongsToMany(Post::class, 'post_category');
    }

    /**
     * @return mixed
     * @author Sang Nguyen
     */
    public function parent()
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    /**
     * @return mixed
     * @author Sang Nguyen
     */
    public function children()
    {
        return $this->hasMany(Category::class, 'parent_id');
    }
}
