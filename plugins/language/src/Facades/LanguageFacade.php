<?php

namespace Botble\Language\Facades;

use Botble\Language\Language;
use Illuminate\Support\Facades\Facade;

class LanguageFacade extends Facade
{

    /**
     * @return string
     * @author Sang Nguyen
     */
    protected static function getFacadeAccessor()
    {
        return Language::class;
    }
}
