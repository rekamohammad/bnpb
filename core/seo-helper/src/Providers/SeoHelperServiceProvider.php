<?php

namespace Botble\SeoHelper\Providers;

use Botble\Base\Supports\Helper;
use Botble\SeoHelper\Contracts\SeoHelperContract;
use Botble\SeoHelper\Facades\SeoHelperFacade;
use Botble\SeoHelper\SeoHelper;
use Botble\SeoHelper\SeoMeta;
use Botble\SeoHelper\SeoOpenGraph;
use Botble\SeoHelper\SeoTwitter;
use Illuminate\Foundation\AliasLoader;
use Illuminate\Support\ServiceProvider;
use Botble\SeoHelper\Contracts\SeoMetaContract;
use Botble\SeoHelper\Contracts\SeoOpenGraphContract;
use Botble\SeoHelper\Contracts\SeoTwitterContract;

/**
 * Class SEOServiceProvider
 * @package Botble\SEO
 * @author Sang Nguyen
 * @since 02/12/2015 14:09 PM
 */
class SeoHelperServiceProvider extends ServiceProvider
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

        $this->app->bind(SeoMetaContract::class, SeoMeta::class);
        $this->app->bind(SeoHelperContract::class, SeoHelper::class);
        $this->app->bind(SeoOpenGraphContract::class, SeoOpenGraph::class);
        $this->app->bind(SeoTwitterContract::class, SeoTwitter::class);

        AliasLoader::getInstance()->alias('SeoHelper', SeoHelperFacade::class);

        Helper::autoload(__DIR__ . '/../../helpers');
    }

    /**
     * Boot the service provider.
     * @author Sang Nguyen
     */
    public function boot()
    {
        $this->loadRoutesFrom(__DIR__.'/../../routes/web.php');
        $this->mergeConfigFrom(__DIR__ . '/../../config/seo-helper.php', 'seo-helper');
        $this->loadViewsFrom(__DIR__ . '/../../resources/views', 'seo-helper');
        $this->loadTranslationsFrom(__DIR__ . '/../../resources/lang', 'seo-helper');

        if ($this->app->runningInConsole()) {
            $this->publishes([__DIR__ . '/../../config/seo-helper.php' => config_path('seo-helper.php')], 'config');
            $this->publishes([__DIR__ . '/../../resources/views' => resource_path('views/vendor/seo-helper')], 'views');
            $this->publishes([__DIR__ . '/../../resources/lang' => resource_path('lang/vendor/seo-helper')], 'lang');

            $this->publishes([__DIR__ . '/../../resources/assets' => resource_path('assets/core')], 'resources');
            $this->publishes([__DIR__ . '/../../public/assets' => public_path('vendor/core'),], 'assets');
        }

        $this->app->register(HookServiceProvider::class);
    }
}
