<?php

namespace Botble\LogViewer\Facades;

use Illuminate\Support\Facades\Facade;

class LogStyler extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     * @author ARCANEDEV <arcanedev.maroc@gmail.com>
     */
    protected static function getFacadeAccessor()
    {
        return 'botble::log-viewer.styler';
    }
}
