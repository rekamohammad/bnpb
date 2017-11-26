<?php

namespace Botble\Base\Facades;

use Illuminate\Support\Facades\Facade;
use Botble\Base\Supports\DashboardMenu;

class DashboardMenuFacade extends Facade
{
    /**
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return DashboardMenu::class;
    }
}
