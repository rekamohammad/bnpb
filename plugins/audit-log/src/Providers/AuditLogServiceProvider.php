<?php

namespace Botble\AuditLog\Providers;

use Botble\AuditLog\Models\AuditHistory;
use Botble\AuditLog\Repositories\Caches\AuditLogCacheDecorator;
use Botble\AuditLog\Repositories\Eloquent\AuditLogRepository;
use Botble\AuditLog\Repositories\Interfaces\AuditLogInterface;
use Botble\Support\Services\Cache\Cache;
use Illuminate\Support\ServiceProvider;

/**
 * Class AuditLogServiceProvider
 * @package Botble\AuditLog
 * @author Sang Nguyen
 * @since 02/07/2016 09:05 AM
 */
class AuditLogServiceProvider extends ServiceProvider
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
            $this->app->singleton(AuditLogInterface::class, function () {
                return new AuditLogCacheDecorator(new AuditLogRepository(new AuditHistory()), new Cache($this->app['cache'], AuditLogRepository::class));
            });
        } else {
            $this->app->singleton(AuditLogInterface::class, function () {
                return new AuditLogRepository(new AuditHistory());
            });
        }
    }

    /**
     * Boot the service provider.
     * @author Sang Nguyen
     */
    public function boot()
    {
        $this->app->register(EventServiceProvider::class);

        $this->loadRoutesFrom(__DIR__.'/../../routes/web.php');
        $this->mergeConfigFrom(__DIR__ . '/../../config/audit-log.php', 'audit-logs');
        $this->loadViewsFrom(__DIR__ . '/../../resources/views', 'audit-logs');
        $this->loadTranslationsFrom(__DIR__ . '/../../resources/lang', 'audit-logs');

        if ($this->app->runningInConsole()) {
            $this->loadMigrationsFrom(__DIR__ . '/../../database/migrations');

            $this->publishes([__DIR__ . '/../../resources/lang' => resource_path('lang/vendor/audit-logs')], 'lang');
            $this->publishes([__DIR__ . '/../../config/audit-log.php' => config_path('audit-log.php')], 'config');
        }

        $this->app->register(HookServiceProvider::class);
    }
}
