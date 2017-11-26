<?php

namespace Botble\Widget\Providers;

use Botble\Widget\Commands\WidgetCreateCommand;
use Botble\Widget\Facades\AsyncFacade;
use Botble\Widget\Facades\WidgetGroupFacade;
use Botble\Widget\Factories\AsyncWidgetFactory;
use Botble\Widget\Factories\WidgetFactory;
use Botble\Widget\Misc\LaravelApplicationWrapper;
use Botble\Widget\Facades\WidgetFacade;
use Botble\Widget\WidgetGroupCollection;
use Botble\Widget\Widgets\Text;
use Illuminate\Foundation\AliasLoader;
use Illuminate\Support\ServiceProvider;
use Botble\Base\Supports\Helper;
use Botble\Widget\Models\Widget;
use Botble\Widget\Repositories\Caches\WidgetCacheDecorator;
use Botble\Widget\Repositories\Eloquent\WidgetRepository;
use Botble\Widget\Repositories\Interfaces\WidgetInterface;
use Botble\Support\Services\Cache\Cache;
use WidgetGroup;

class WidgetServiceProvider extends ServiceProvider
{
    /**
     * @var \Illuminate\Foundation\Application
     */
    protected $app;

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {

        if (setting('enable_cache', false)) {
            $this->app->singleton(WidgetInterface::class, function () {
                return new WidgetCacheDecorator(new WidgetRepository(new Widget()), new Cache($this->app['cache'], WidgetRepository::class));
            });
        } else {
            $this->app->singleton(WidgetInterface::class, function () {
                return new WidgetRepository(new Widget());
            });
        }

        $this->app->bind('botble.widget', function () {
            return new WidgetFactory(new LaravelApplicationWrapper());
        });

        $this->app->bind('botble.async-widget', function () {
            return new AsyncWidgetFactory(new LaravelApplicationWrapper());
        });

        $this->app->singleton('botble.widget-group-collection', function () {
            return new WidgetGroupCollection(new LaravelApplicationWrapper());
        });

        if ($this->app->runningInConsole()) {
            $this->commands([WidgetCreateCommand::class]);
        }

        $this->app->alias('botble.widget', WidgetFactory::class);
        $this->app->alias('botble.async-widget', AsyncWidgetFactory::class);
        $this->app->alias('botble.widget-group-collection', WidgetGroupCollection::class);

        AliasLoader::getInstance()->alias('Widget', WidgetFacade::class);
        AliasLoader::getInstance()->alias('AsyncWidget', AsyncFacade::class);
        AliasLoader::getInstance()->alias('WidgetGroup', WidgetGroupFacade::class);
    }

    /**
     * Bootstrap the application events.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadRoutesFrom(__DIR__ . '/../../routes/web.php');
        $this->mergeConfigFrom(__DIR__ . '/../../config/widget.php', 'widgets');
        $this->loadViewsFrom(__DIR__ . '/../../resources/views', 'widgets');
        $this->loadTranslationsFrom(__DIR__ . '/../../resources/lang', 'widgets');

        if ($this->app->runningInConsole()) {
            $this->loadMigrationsFrom(__DIR__ . '/../../database/migrations');

            $this->publishes([__DIR__ . '/../../resources/views' => resource_path('views/vendor/widgets')], 'views');
            $this->publishes([__DIR__ . '/../../resources/lang' => resource_path('lang/vendor/widgets')], 'lang');
            $this->publishes([__DIR__ . '/../../config/widget.php' => config_path('widget.php')], 'config');
            $this->publishes([__DIR__ . '/../../resources/assets' => resource_path('assets/core')], 'resources');
            $this->publishes([__DIR__ . '/../../public/assets' => public_path('vendor/core'),], 'assets');
        }

        Helper::autoload(__DIR__ . '/../../helpers');

        WidgetGroup::setGroup([
            'id' => 'home_left',
            'name' => 'Home Left Sidebar',
            'description' => 'This is left sidebar section',
        ]);

        WidgetGroup::setGroup([
            'id' => 'home_center',
            'name' => 'Home Center Sidebar',
            'description' => 'This is center sidebar section',
        ]);

        WidgetGroup::setGroup([
            'id' => 'home_right',
            'name' => 'Home Right Sidebar',
            'description' => 'This is right sidebar section',
        ]);

        WidgetGroup::setGroup([
            'id' => 'primary_sidebar',
            'name' => 'Primary sidebar',
            'description' => 'This is primary sidebar section',
        ]);

        register_widget(Text::class);
    }
}
