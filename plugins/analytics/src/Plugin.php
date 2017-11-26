<?php

namespace Botble\Analytics;

use Botble\Base\Interfaces\PluginInterface;
use Botble\Base\Supports\Commands\Permission;

class Plugin implements PluginInterface
{

    /**
     * @return array
     * @author Sang Nguyen
     */
    public static function permissions()
    {
        Permission::removePermission([
            [
                'flag' => 'analytics.list',
            ],
            [
                'flag' => 'analytics.create',
            ],
            [
                'flag' => 'analytics.edit',
            ],
            [
                'flag' => 'analytics.delete',
            ]
        ]);

        return [
            [
                'name' => 'Analytics',
                'flag' => 'analytics.general',
                'is_feature' => true,
            ],
            [
                'name' => 'Top Page',
                'flag' => 'analytics.page',
                'parent_flag' => 'analytics.general',
            ],
            [
                'name' => 'Top Browser',
                'flag' => 'analytics.browser',
                'parent_flag' => 'analytics.general',
            ],
            [
                'name' => 'Top Referrer',
                'flag' => 'analytics.referrer',
                'parent_flag' => 'analytics.general',
            ]
        ];
    }

    /**
     * @author Sang Nguyen
     */
    public static function activate()
    {
        Permission::registerPermission(self::permissions());
    }

    /**
     * @author Sang Nguyen
     */
    public static function deactivate()
    {

    }

    /**
     * @author Sang Nguyen
     */
    public static function remove()
    {
        Permission::removePermission(self::permissions());
    }
}