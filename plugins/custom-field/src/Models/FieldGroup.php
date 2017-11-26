<?php

namespace Botble\CustomField\Models;

use Eloquent;
use Botble\CustomField\Models\Contracts\FieldGroupContract;

class FieldGroup extends Eloquent implements FieldGroupContract
{
    protected $table = 'field_groups';

    protected $primaryKey = 'id';

    protected $fillable = ['order', 'rules', 'title', 'status'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function fieldItems()
    {
        return $this->hasMany(FieldItem::class, 'field_group_id');
    }
}
