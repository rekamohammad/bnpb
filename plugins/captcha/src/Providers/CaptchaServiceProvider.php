<?php

namespace Botble\Captcha\Providers;

use Botble\Captcha\Facades\CaptchaFacade;
use Botble\Captcha\Captcha;
use Illuminate\Foundation\AliasLoader;
use Illuminate\Support\ServiceProvider;

class CaptchaServiceProvider extends ServiceProvider
{
    /**
     * @var \Illuminate\Foundation\Application
     */
    protected $app;

    /**
     * Register the service provider.
     * @author ARCANEDEV <arcanedev.maroc@gmail.com>
     */
    public function register()
    {
        $this->app->bind(Captcha::class, function () {
            return new Captcha(
                config('captcha.secret'),
                config('captcha.sitekey'),
                config('captcha.lang'),
                config('captcha.attributes', [])
            );
        });
        AliasLoader::getInstance()->alias('Captcha', CaptchaFacade::class);
    }

    /**
     * Bootstrap the application events.
     * @author ARCANEDEV <arcanedev.maroc@gmail.com>
     */
    public function boot()
    {
        $this->app['validator']->extend('captcha', function ($attribute, $value) {
            unset($attribute);
            $ip = $this->app['request']->getClientIp();

            return $this->app[Captcha::class]->verify($value, $ip);
        });

        if ($this->app->bound('form')) {
            $this->app['form']->macro('captcha', function ($name = null, array $attributes = []) {
                return $this->app['botble::no-captcha']->display($name, $attributes);
            });
        }
        $this->mergeConfigFrom(__DIR__ . '/../../config/captcha.php', 'captcha');

        if ($this->app->runningInConsole()) {
            $this->publishes([__DIR__ . '/../../config/captcha.php' => config_path('captcha.php')], 'config');
        }
    }
}
