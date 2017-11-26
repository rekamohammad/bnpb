<?php

namespace Botble\Note\Providers;

use Botble\Support\Services\Cache\Cache;
use Botble\Base\Supports\Helper;
use Botble\Note\Models\Note;
use Botble\Note\Repositories\Caches\NoteCacheDecorator;
use Botble\Note\Repositories\Eloquent\NoteRepository;
use Botble\Note\Repositories\Interfaces\NoteInterface;
use Illuminate\Support\ServiceProvider;

/**
 * Class NoteServiceProvider
 * @package Botble\Note
 * @author Sang Nguyen
 * @since 07/02/2016 09:50 AM
 */
class NoteServiceProvider extends ServiceProvider
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
            $this->app->singleton(NoteInterface::class, function () {
                return new NoteCacheDecorator(new NoteRepository(new Note()), new Cache($this->app['cache'], NoteRepository::class));
            });
        } else {
            $this->app->singleton(NoteInterface::class, function () {
                return new NoteRepository(new Note());
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
        $this->mergeConfigFrom(__DIR__ . '/../../config/note.php', 'note');
        $this->loadViewsFrom(__DIR__ . '/../../resources/views', 'note');

        if ($this->app->runningInConsole()) {
            $this->loadMigrationsFrom(__DIR__ . '/../../database/migrations');

            $this->publishes([__DIR__ . '/../../resources/views' => resource_path('views/vendor/note')], 'views');
            $this->publishes([__DIR__ . '/../../config/note.php' => config_path('note.php')], 'config');
        }

        $this->app->register(HookServiceProvider::class);
    }
}
