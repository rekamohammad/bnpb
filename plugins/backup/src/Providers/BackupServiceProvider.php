<?php

namespace Botble\Backup\Providers;

use Botble\Base\Events\SessionStarted;
use Botble\Base\Supports\Helper;
use Event;
use Illuminate\Support\ServiceProvider;

class BackupServiceProvider extends ServiceProvider
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
        Helper::autoload(__DIR__ . '/../../helpers');
    }

    /**
     * @author Sang Nguyen
     */
    public function boot()
    {
        $this->loadRoutesFrom(__DIR__.'/../../routes/web.php');
        $this->mergeConfigFrom(__DIR__ . '/../../config/backup.php', 'backup');
        $this->loadViewsFrom(__DIR__ . '/../../resources/views', 'backup');
        $this->loadTranslationsFrom(__DIR__ . '/../../resources/lang', 'backup');

        if ($this->app->runningInConsole()) {
            $this->publishes([__DIR__ . '/../../resources/views' => resource_path('views/vendor/backup')], 'views');
            $this->publishes([__DIR__ . '/../../resources/lang' => resource_path('lang/vendor/backup')], 'lang');
            $this->publishes([__DIR__ . '/../../config/backup.php' => config_path('backup.php')], 'config');

            $this->publishes([__DIR__ . '/../../resources/assets' => resource_path('assets/core/plugins/backup')], 'resources');
            $this->publishes([__DIR__ . '/../../public/assets' => public_path('vendor/core/plugins/backup')], 'assets');
        }

        $this->app->register(HookServiceProvider::class);

        Event::listen(SessionStarted::class, function () {
            // dashboard_menu()->registerItem([
            //     'id' => 'cms-plugin-backup',
            //     'priority' => 8,
            //     'parent_id' => 'cms-core-platform-administration',
            //     'name' => trans('backup::backup.menu_name'),
            //     'icon' => null,
            //     'url' => route('backups.list'),
            //     'permissions' => ['backups.list'],
            // ]);
        });
    }
}
