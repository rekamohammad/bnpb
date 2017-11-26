<?php

namespace Botble\Analytics\Providers;

use Botble\Analytics\Analytics;
use Botble\Analytics\AnalyticsClient;
use Botble\Analytics\AnalyticsClientFactory;
use Botble\Analytics\Facades\AnalyticsFacade;
use Illuminate\Foundation\AliasLoader;
use Illuminate\Support\ServiceProvider;
use Botble\Analytics\Exceptions\InvalidConfiguration;

class AnalyticsServiceProvider extends ServiceProvider
{

    /**
     * @var \Illuminate\Foundation\Application
     */
    protected $app;

    /**
     * Register the service provider.
     * @author Freek Van der Herten <freek@spatie.be>
     * @modified Sang Nguyen
     */
    public function register()
    {

        $this->app->bind(AnalyticsClient::class, function () {
            return AnalyticsClientFactory::createForConfig(config('analytics'));
        });

        $this->app->bind(Analytics::class, function () {

            if (empty(config('analytics.view_id'))) {
                throw InvalidConfiguration::viewIdNotSpecified();
            }

            if (! file_exists(config('analytics.service_account_credentials_json'))) {
                throw InvalidConfiguration::credentialsJsonDoesNotExist(config('analytics.service_account_credentials_json'));
            }

            return new Analytics(app(AnalyticsClient::class), config('analytics.view_id'));
        });

        AliasLoader::getInstance()->alias('Analytics', AnalyticsFacade::class);
    }

    /**
     * Bootstrap the application events.
     * @author Sang Nguyen
     */
    public function boot()
    {
        $this->loadRoutesFrom(__DIR__.'/../../routes/web.php');
        $this->mergeConfigFrom(__DIR__ . '/../../config/analytics.php', 'analytics');
        $this->loadViewsFrom(__DIR__ . '/../../resources/views', 'analytics');
        $this->loadTranslationsFrom(__DIR__ . '/../../resources/lang', 'analytics');

        if ($this->app->runningInConsole()) {
            $this->publishes([__DIR__ . '/../../config/analytics.php' => config_path('analytics.php')], 'config');
            $this->publishes([__DIR__ . '/../../resources/views' => resource_path('views/vendor/analytics')], 'views');
            $this->publishes([__DIR__ . '/../../resources/lang' => resource_path('lang/vendor/analytics')], 'lang');
        }

        $this->app->register(HookServiceProvider::class);
    }
}
