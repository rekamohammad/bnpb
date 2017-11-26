<?php

namespace Botble\Base\Providers;

use Botble\Base\Repositories\Interfaces\PluginInterface;
use Illuminate\Support\Collection;
use Illuminate\Support\ServiceProvider;
use Schema;

class PluginServiceProvider extends ServiceProvider
{
    /**
     * @author Sang Nguyen
     * @param PluginInterface $pluginRepository
     */
    public function boot(PluginInterface $pluginRepository)
    {
        if (check_database_connection() && Schema::hasTable('plugins')) {
            $plugins = $pluginRepository->allBy(['status' => 1]);
            if ($plugins instanceof Collection && !empty($plugins)) {
                foreach ($plugins as $plugin) {
                    if (class_exists($plugin->provider)) {
                        $this->app->register($plugin->provider);
                    } else {
                        $pluginRepository->deleteBy(['provider' => $plugin->provider]);
                    }
                }
            }
        }
    }
}
