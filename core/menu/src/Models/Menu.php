<?php

namespace Botble\Menu\Models;

use Eloquent;

class Menu extends Eloquent
{

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'menus';

    /**
     * @return mixed
     * @author Sang Nguyen
     */
    public function menuContent()
    {
        return $this->hasMany(MenuContent::class, 'menu_id');
    }
}
