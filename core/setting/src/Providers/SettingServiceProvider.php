<?php

namespace Botble\Setting\Providers;

use Botble\Base\Supports\Helper;
use Botble\Setting\Facades\SettingFacade;
use Botble\Setting\Models\Setting as SettingModel;
use Illuminate\Foundation\AliasLoader;
use Illuminate\Support\ServiceProvider;
use Botble\Setting\Repositories\Caches\SettingCacheDecorator;
use Botble\Setting\Repositories\Eloquent\SettingRepository;
use Botble\Setting\Repositories\Interfaces\SettingInterface;
use Botble\Support\Services\Cache\Cache;

class SettingServiceProvider extends ServiceProvider
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
        AliasLoader::getInstance()->alias('Setting', SettingFacade::class);

        Helper::autoload(__DIR__ . '/../../helpers');

        if (function_exists('setting') && setting('enable_cache', false)) {
            $this->app->singleton(SettingInterface::class, function () {
                return new SettingCacheDecorator(new SettingRepository(new SettingModel()), new Cache($this->app['cache'], SettingRepository::class));
            });
        } else {
            $this->app->singleton(SettingInterface::class, function () {
                return new SettingRepository(new SettingModel());
            });
        }
    }

    /**
     * @author Sang Nguyen
     */
    public function boot()
    {
        $this->loadRoutesFrom(__DIR__.'/../../routes/web.php');
        $this->mergeConfigFrom(__DIR__ . '/../../config/setting.php', 'settings');
        $this->loadViewsFrom(__DIR__ . '/../../resources/views', 'settings');
        $this->loadTranslationsFrom(__DIR__ . '/../../resources/lang', 'settings');

        if ($this->app->runningInConsole()) {
            $this->loadMigrationsFrom(__DIR__ . '/../../database/migrations');

            $this->publishes([__DIR__ . '/../../resources/views' => resource_path('views/vendor/settings')], 'views');
            $this->publishes([__DIR__ . '/../../resources/lang' => resource_path('lang/vendor/settings')], 'lang');
            $this->publishes([__DIR__ . '/../../config/setting.php' => config_path('setting.php')], 'config');
        }
    }
}
