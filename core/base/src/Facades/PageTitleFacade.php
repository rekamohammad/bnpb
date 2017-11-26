<?php

namespace Botble\Base\Facades;

use Illuminate\Support\Facades\Facade;
use Botble\Base\Supports\PageTitle;

class PageTitleFacade extends Facade
{
    /**
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return PageTitle::class;
    }
}
