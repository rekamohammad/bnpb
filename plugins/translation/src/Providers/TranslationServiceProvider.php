<?php

namespace Botble\Translation\Providers;

use Botble\Base\Events\SessionStarted;
use Botble\Translation\Console\CleanCommand;
use Botble\Translation\Console\ExportCommand;
use Botble\Translation\Console\FindCommand;
use Botble\Translation\Console\ImportCommand;
use Botble\Translation\Console\ResetCommand;
use Botble\Translation\Manager;
use Event;
use Illuminate\Support\ServiceProvider;

class TranslationServiceProvider extends ServiceProvider
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
        $this->app->bind('translation-manager', Manager::class);

        $this->commands([
            ImportCommand::class,
            FindCommand::class,
        ]);

        if ($this->app->runningInConsole()) {
            $this->commands([
                ResetCommand::class,
                ExportCommand::class,
                CleanCommand::class
            ]);
        }

    }

    /**
     * @author Sang Nguyen
     */
    public function boot()
    {
        $this->loadRoutesFrom(__DIR__.'/../../routes/web.php');
        $this->mergeConfigFrom(__DIR__ . '/../../config/translation.php', 'translation');
        $this->loadViewsFrom(__DIR__ . '/../../resources/views', 'translations');
        $this->loadTranslationsFrom(__DIR__ . '/../../resources/lang', 'translations');


        if ($this->app->runningInConsole()) {
            $this->loadMigrationsFrom(__DIR__ . '/../../database/migrations');

            $this->publishes([__DIR__ . '/../../resources/views' => resource_path('views/vendor/translations')], 'views');
            $this->publishes([__DIR__ . '/../../resources/lang' => resource_path('lang/vendor/translations')], 'lang');
            $this->publishes([__DIR__ . '/../../config/translation.php' => config_path('translation.php')], 'config');
            $this->publishes([__DIR__ . '/../../resources/assets' => resource_path('assets/core/plugins/translation')], 'resources');
            $this->publishes([__DIR__ . '/../../public/assets' => public_path('vendor/core/plugins/translation')], 'assets');
        }

        Event::listen(SessionStarted::class, function () {
            dashboard_menu()->registerItem([
                'id' => 'cms-plugin-translation',
                'priority' => 6,
                'parent_id' => 'cms-core-platform-administration',
                'name' => trans('translations::translation.menu_name'),
                'icon' => null,
                'url' => route('translations.list'),
                'permissions' => ['translations.list'],
            ]);
        });
    }
}
