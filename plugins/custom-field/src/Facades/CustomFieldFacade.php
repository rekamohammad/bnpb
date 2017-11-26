<?php

namespace Botble\CustomField\Facades;

use Botble\CustomField\CustomField;
use Illuminate\Support\Facades\Facade;

class CustomFieldFacade extends Facade
{
    /**
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return CustomField::class;
    }
}
