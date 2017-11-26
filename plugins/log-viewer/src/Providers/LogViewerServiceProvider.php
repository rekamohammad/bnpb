<?php

namespace Botble\LogViewer\Providers;

use Botble\Base\Events\SessionStarted;
use Botble\Base\Supports\Helper;
use Botble\LogViewer\Facades\LogViewerFacade;
use Botble\LogViewer\LogViewer;
use Event;
use Illuminate\Foundation\AliasLoader;
use Illuminate\Support\ServiceProvider;
use Botble\LogViewer\Contracts;
use Botble\LogViewer\Utilities;

class LogViewerServiceProvider extends ServiceProvider
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

        $this->app->bind('botble::log-viewer', LogViewer::class);
        AliasLoader::getInstance()->alias('LogViewer', LogViewerFacade::class);

        Helper::autoload(__DIR__ . '/../../helpers');

        $this->app->singleton('botble::log-viewer.levels', function ($app) {
            return new Utilities\LogLevels($app['translator'], config('log-viewer.locale'));
        });
        $this->app->bind(Contracts\Utilities\LogLevels::class, 'botble::log-viewer.levels');

        $this->app->singleton('botble::log-viewer.styler', Utilities\LogStyler::class);
        $this->app->bind(Contracts\Utilities\LogStyler::class, 'botble::log-viewer.styler');

        $this->app->singleton('botble::log-viewer.menu', Utilities\LogMenu::class);
        $this->app->bind(Contracts\Utilities\LogMenu::class, 'botble::log-viewer.menu');

        $this->app->singleton('botble::log-viewer.filesystem', function ($app) {

            $filesystem = new Utilities\Filesystem($app['files'], config('log-viewer.storage-path'));

            $filesystem->setPattern(
                config('log-viewer.pattern.prefix', Utilities\Filesystem::PATTERN_PREFIX),
                config('log-viewer.pattern.date', Utilities\Filesystem::PATTERN_DATE),
                config('log-viewer.pattern.extension', Utilities\Filesystem::PATTERN_EXTENSION)
            );

            return $filesystem;
        });
        $this->app->bind(Contracts\Utilities\Filesystem::class, 'botble::log-viewer.filesystem');

        $this->app->singleton('botble::log-viewer.factory', Utilities\Factory::class);
        $this->app->bind(Contracts\Utilities\Factory::class, 'botble::log-viewer.factory');

        $this->app->singleton('botble::log-viewer.checker', Utilities\LogChecker::class);
        $this->app->bind(Contracts\Utilities\LogChecker::class, 'botble::log-viewer.checker');

    }

    /**
     * @author Sang Nguyen
     */
    public function boot()
    {
        $this->loadRoutesFrom(__DIR__.'/../../routes/web.php');
        $this->mergeConfigFrom(__DIR__ . '/../../config/log-viewer.php', 'log-viewer');
        $this->loadViewsFrom(__DIR__ . '/../../resources/views', 'log-viewer');
        $this->loadTranslationsFrom(__DIR__ . '/../../resources/lang', 'log-viewer');

        if ($this->app->runningInConsole()) {
            $this->loadMigrationsFrom(__DIR__ . '/../../database/migrations');

            $this->publishes([__DIR__ . '/../../resources/views' => resource_path('views/vendor/log-viewer')], 'views');
            $this->publishes([__DIR__ . '/../../resources/lang' => resource_path('lang/vendor/log-viewer')], 'lang');
            $this->publishes([__DIR__ . '/../../config/log-viewer.php' => config_path('log-viewer.php')], 'config');
        }

        Event::listen(SessionStarted::class, function () {
            dashboard_menu()->registerItem([
                'id' => 'cms-plugin-system-logs',
                'priority' => 7,
                'parent_id' => 'cms-core-platform-administration',
                'name' => trans('log-viewer::log-viewer.menu_name'),
                'icon' => null,
                'url' => route('log-viewer::dashboard'),
                'permissions' => ['log-viewer::dashboard'],
            ]);
        });
    }
}
