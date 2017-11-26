<?php

namespace Botble\Analytics;

use Google_Client;
use Google_Service_Analytics;
use Illuminate\Contracts\Cache\Repository;

class AnalyticsClientFactory
{
    /**
     * @param array $analyticsConfig
     * @return AnalyticsClient
     * @author Freek Van der Herten <freek@spatie.be>
     */
    public static function createForConfig(array $analyticsConfig)
    {
        $authenticatedClient = self::createAuthenticatedGoogleClient($analyticsConfig);

        $googleService = new Google_Service_Analytics($authenticatedClient);

        return self::createAnalyticsClient($analyticsConfig, $googleService);
    }

    /**
     * @param array $config
     * @return Google_Client
     * @author Freek Van der Herten <freek@spatie.be>
     */
    public static function createAuthenticatedGoogleClient(array $config)
    {
        $client = new Google_Client();

        $client->setClassConfig(
            'Google_Cache_File',
            'directory',
            $config['cache_location'] ? $config['cache_location'] : storage_path('app/laravel-google-analytics/google-cache/')
        );

        $credentials = $client->loadServiceAccountJson(
            $config['service_account_credentials_json'],
            ['https://www.googleapis.com/auth/analytics.readonly']
        );

        $client->setAssertionCredentials($credentials);

        return $client;
    }

    /**
     * @param array $analyticsConfig
     * @param Google_Service_Analytics $googleService
     * @return AnalyticsClient
     * @author Freek Van der Herten <freek@spatie.be>
     */
    protected static function createAnalyticsClient(array $analyticsConfig, Google_Service_Analytics $googleService)
    {
        $client = new AnalyticsClient($googleService, app(Repository::class));

        $client->setCacheLifeTimeInMinutes($analyticsConfig['cache_lifetime_in_minutes']);

        return $client;
    }
}
