<?php

namespace Botble\Theme\Providers;

use Botble\Theme\Facades\ManagerFacade;
use Botble\Base\Supports\Helper;
use Botble\Theme\Commands\ThemeCreateCommand;
use Botble\Theme\Commands\ThemeRemoveCommand;
use Botble\Theme\Contracts\Theme as ThemeContract;
use Botble\Theme\Facades\ThemeFacade;
use Botble\Theme\Facades\ThemeOptionFacade;
use Botble\Theme\Theme;
use Illuminate\Foundation\AliasLoader;
use Illuminate\Support\ServiceProvider;

class ThemeServiceProvider extends ServiceProvider
{
    /**
     * @var \Illuminate\Foundation\Application
     */
    protected $app;

    /**
     * @author Sang Nguyen
     */
    public function register()
    {
        AliasLoader::getInstance()->alias('Theme', ThemeFacade::class);
        AliasLoader::getInstance()->alias('ThemeOption', ThemeOptionFacade::class);
        AliasLoader::getInstance()->alias('ThemeManager', ManagerFacade::class);

        $this->app->bind(ThemeContract::class, Theme::class);

        if ($this->app->runningInConsole()) {
            $this->commands([
                ThemeCreateCommand::class,
                ThemeRemoveCommand::class,
            ]);
        }

        Helper::autoload(__DIR__ . '/../../helpers');
    }

    /**
     * @author Sang Nguyen
     */
    public function boot()
    {
        $this->mergeConfigFrom(__DIR__ . '/../../config/theme.php', 'theme');
        $this->loadRoutesFrom(__DIR__ . '/../../routes/web.php');
        $this->loadViewsFrom(__DIR__ . '/../../resources/views', 'theme');
        $this->loadTranslationsFrom(__DIR__ . '/../../resources/lang', 'theme');

        if ($this->app->runningInConsole()) {
            $this->publishes([__DIR__ . '/../../config/theme.php' => config_path('theme.php')], 'config');
        }
    }
}
