<?php

namespace Botble\Block\Providers;

use Botble\Base\Events\SessionStarted;
use Botble\Block\Models\Block;
use Event;
use Illuminate\Support\ServiceProvider;
use Botble\Block\Repositories\Caches\BlockCacheDecorator;
use Botble\Block\Repositories\Eloquent\BlockRepository;
use Botble\Block\Repositories\Interfaces\BlockInterface;
use Botble\Support\Services\Cache\Cache;
use Botble\Base\Supports\Helper;

class BlockServiceProvider extends ServiceProvider
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
        if (setting('enable_cache', false)) {
            $this->app->singleton(BlockInterface::class, function () {
                return new BlockCacheDecorator(new BlockRepository(new Block()), new Cache($this->app['cache'], BlockRepository::class));
            });
        } else {
            $this->app->singleton(BlockInterface::class, function () {
                return new BlockRepository(new Block());
            });
        }

        Helper::autoload(__DIR__ . '/../../helpers');
    }

    /**
     * @author Sang Nguyen
     */
    public function boot()
    {
        $this->loadTranslationsFrom(__DIR__ . '/../../resources/lang', 'block');
        $this->loadRoutesFrom(__DIR__.'/../../routes/web.php');
        $this->mergeConfigFrom(__DIR__ . '/../../config/block.php', 'block');
        $this->loadViewsFrom(__DIR__ . '/../../resources/views', 'block');

        if ($this->app->runningInConsole()) {
            $this->loadMigrationsFrom(__DIR__ . '/../../database/migrations');

            $this->publishes([__DIR__ . '/../../resources/views' => resource_path('views/vendor/block')], 'views');
            $this->publishes([__DIR__ . '/../../resources/lang' => resource_path('lang/vendor/block')], 'lang');
            $this->publishes([__DIR__ . '/../../config/block.php' => config_path('block.php')], 'config');
        }

        Event::listen(SessionStarted::class, function () {
          /**  dashboard_menu()->registerItem([
                'id' => 'cms-plugins-block',
                'priority' => 6,
                'parent_id' => null,
                'name' => trans('block::block.menu'),
                'icon' => 'fa fa-code',
                'url' => route('block.list'),
                'permissions' => ['block.list'],
            ]);
			
			*/
        });

        $this->app->register(HookServiceProvider::class);
    }
}
