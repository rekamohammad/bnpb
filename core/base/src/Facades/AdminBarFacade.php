<?php

namespace Botble\Base\Facades;

use Illuminate\Support\Facades\Facade;
use Botble\Base\Supports\AdminBar;

class AdminBarFacade extends Facade
{
    /**
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return AdminBar::class;
    }
}
