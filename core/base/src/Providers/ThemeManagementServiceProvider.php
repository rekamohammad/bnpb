<?php

namespace Botble\Base\Providers;

use Botble\Base\Supports\Helper;
use Illuminate\Support\ServiceProvider;

class ThemeManagementServiceProvider extends ServiceProvider
{
    /**
     * @author Sang Nguyen
     */
    public function boot()
    {
        if (check_database_connection() && !empty(setting('theme'))) {
            Helper::autoload(public_path() . DIRECTORY_SEPARATOR . config('theme.themeDir') . DIRECTORY_SEPARATOR . setting('theme') . '/functions');
        }
    }
}
