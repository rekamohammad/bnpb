<?php

namespace Botble\CustomField\Providers;

use Botble\Base\Events\SessionStarted;
use Botble\Support\Services\Cache\Cache;
use Botble\Base\Supports\Helper;
use Botble\CustomField\Facades\CustomFieldFacade;
use Botble\CustomField\Models\CustomField;
use Botble\CustomField\Models\FieldGroup;
use Botble\CustomField\Models\FieldItem;
use Botble\CustomField\Repositories\Caches\CustomFieldCacheDecorator;
use Botble\CustomField\Repositories\Eloquent\CustomFieldRepository;
use Botble\CustomField\Repositories\Caches\FieldGroupCacheDecorator;
use Botble\CustomField\Repositories\Eloquent\FieldGroupRepository;
use Botble\CustomField\Repositories\Caches\FieldItemCacheDecorator;
use Botble\CustomField\Repositories\Eloquent\FieldItemRepository;
use Botble\CustomField\Repositories\Interfaces\CustomFieldInterface;
use Botble\CustomField\Repositories\Interfaces\FieldGroupInterface;
use Botble\CustomField\Repositories\Interfaces\FieldItemInterface;
use Event;
use Illuminate\Foundation\AliasLoader;
use Illuminate\Support\ServiceProvider;

class CustomFieldServiceProvider extends ServiceProvider
{
    /**
     * @var \Illuminate\Foundation\Application
     */
    protected $app;

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        Helper::autoload(__DIR__ . '/../../helpers');

        $loader = AliasLoader::getInstance();
        $loader->alias('CustomField', CustomFieldFacade::class);

        if (setting('enable_cache', false)) {
            $this->app->singleton(CustomFieldInterface::class, function () {
                return new CustomFieldCacheDecorator(new CustomFieldRepository(new CustomField()), new Cache($this->app['cache'], CUSTOM_FIELD_CACHE_GROUP));
            });

            $this->app->singleton(FieldGroupInterface::class, function () {
                return new FieldGroupCacheDecorator(new FieldGroupRepository(new FieldGroup()), new Cache($this->app['cache'], CUSTOM_FIELD_CACHE_GROUP));
            });

            $this->app->singleton(FieldItemInterface::class, function () {
                return new FieldItemCacheDecorator(new FieldItemRepository(new FieldItem()), new Cache($this->app['cache'], CUSTOM_FIELD_CACHE_GROUP));
            });
        } else {
            $this->app->singleton(CustomFieldInterface::class, function () {
                return new CustomFieldRepository(new CustomField());
            });

            $this->app->singleton(FieldGroupInterface::class, function () {
                return new FieldGroupRepository(new FieldGroup());
            });

            $this->app->singleton(FieldItemInterface::class, function () {
                return new FieldItemRepository(new FieldItem());
            });
        }
    }

    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadTranslationsFrom(__DIR__ . '/../../resources/lang', 'custom-field');
        $this->loadRoutesFrom(__DIR__ . '/../../routes/web.php');
        $this->mergeConfigFrom(__DIR__ . '/../../config/custom-field.php', 'custom-field');
        $this->loadViewsFrom(__DIR__ . '/../../resources/views', 'custom-field');

        if ($this->app->runningInConsole()) {
            $this->loadMigrationsFrom(__DIR__ . '/../../database/migrations');

            $this->publishes([__DIR__ . '/../../resources/views' => resource_path('views/vendor/custom-field')], 'views');
            $this->publishes([__DIR__ . '/../../resources/lang' => resource_path('lang/vendor/custom-field')], 'lang');
            $this->publishes([__DIR__ . '/../../config/custom-field.php' => config_path('custom-field.php')], 'config');

            $this->publishes([__DIR__ . '/../../resources/assets' => resource_path('assets/core/plugins/custom-field')], 'resources');
            $this->publishes([__DIR__ . '/../../public/assets' => public_path('vendor/core/plugins/custom-field')], 'assets');
        }

        $this->app->register(HookServiceProvider::class);

        // Event::listen(SessionStarted::class, function () {
        //     dashboard_menu()->registerItem([
        //         'id' => 'cms-plugins-custom-field',
        //         'priority' => 5,
        //         'parent_id' => null,
        //         'name' => trans('custom-field::custom-field.menu_name'),
        //         'icon' => 'icon icon-list',
        //         'url' => route('custom-fields.list'),
        //         'permissions' => ['custom-fields.list'],
        //     ]);
        // });
    }
}
