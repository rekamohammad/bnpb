<?php

namespace Botble\Menu\Models;

use Eloquent;

class MenuContent extends Eloquent
{

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'menu_contents';

    /**
     * @return mixed
     * @author Sang Nguyen
     */
    public function menu()
    {
        return $this->belongsTo(Menu::class, 'menu_id');
    }

    /**
     * @return mixed
     * @author Sang Nguyen
     */
    public function menuNode()
    {
        return $this->hasMany(MenuNode::class, 'menu_content_id');
    }
}
