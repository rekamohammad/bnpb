<?php

namespace Botble\Contact\Providers;

use Botble\Base\Events\SessionStarted;
use Botble\Base\Supports\Helper;
use Botble\Contact\Repositories\Interfaces\ContactInterface;
use Botble\Contact\Models\Contact;
use Botble\Contact\Repositories\Caches\ContactCacheDecorator;
use Botble\Contact\Repositories\Eloquent\ContactRepository;
use Botble\Support\Services\Cache\Cache;
use Event;
use Illuminate\Support\ServiceProvider;

class ContactServiceProvider extends ServiceProvider
{

    /**
     * @var \Illuminate\Foundation\Application
     */
    protected $app;

    /**
     * Register the service provider.
     *
     * @return void
     * @author Sang Nguyen
     */
    public function register()
    {

        if (setting('enable_cache', false)) {
            $this->app->singleton(ContactInterface::class, function () {
                return new ContactCacheDecorator(new ContactRepository(new Contact()), new Cache($this->app['cache'], ContactRepository::class));
            });
        } else {
            $this->app->singleton(ContactInterface::class, function () {
                return new ContactRepository(new Contact());
            });
        }

        Helper::autoload(__DIR__ . '/../../helpers');
    }

    /**
     * Boot the service provider.
     * @author Sang Nguyen
     */
    public function boot()
    {
        $this->loadRoutesFrom(__DIR__.'/../../routes/web.php');
        $this->mergeConfigFrom(__DIR__ . '/../../config/contact.php', 'contact');
        $this->loadViewsFrom(__DIR__ . '/../../resources/views', 'contact');
        $this->loadTranslationsFrom(__DIR__ . '/../../resources/lang', 'contact');

        if ($this->app->runningInConsole()) {
            $this->loadMigrationsFrom(__DIR__ . '/../../database/migrations');

            $this->publishes([__DIR__ . '/../../resources/views' => resource_path('views/vendor/contact')], 'views');
            $this->publishes([__DIR__ . '/../../resources/lang' => resource_path('lang/vendor/contact')], 'lang');
            $this->publishes([__DIR__ . '/../../config/contact.php' => config_path('contact.php')], 'config');
        }

        $this->app->register(HookServiceProvider::class);

        Event::listen(SessionStarted::class, function () {
            dashboard_menu()->registerItem([
                'id' => 'cms-plugins-contact',
                'priority' => 120,
                'parent_id' => null,
                'name' => trans('contact::contact.menu'),
                'icon' => 'fa fa-envelope-o',
                'url' => route('contacts.list'),
                'permissions' => ['contacts.list'],
            ]);
        });
    }
}
