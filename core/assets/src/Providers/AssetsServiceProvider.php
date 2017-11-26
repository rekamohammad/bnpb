<?php

namespace Botble\Assets\Providers;

use Botble\Assets\Facades\AssetsFacade;
use Illuminate\Foundation\AliasLoader;
use Illuminate\Support\ServiceProvider;

/**
 * Class AssetsServiceProvider
 * @package Botble\Assets
 * @author Sang Nguyen
 * @since 22/07/2015 11:23 PM
 */
class AssetsServiceProvider extends ServiceProvider
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
        AliasLoader::getInstance()->alias('Assets', AssetsFacade::class);
    }

    /**
     * @author Sang Nguyen
     */
    public function boot()
    {
        $this->mergeConfigFrom(__DIR__ . '/../../config/assets.php', 'assets');
        if ($this->app->runningInConsole()) {
            $this->publishes([__DIR__ . '/../../config/assets.php' => config_path('assets.php')], 'config');
        }
    }
}
