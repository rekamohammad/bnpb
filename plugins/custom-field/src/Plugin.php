<?php

namespace Botble\CustomField;

use Artisan;
use Botble\Base\Interfaces\PluginInterface;
use Botble\Base\Supports\Commands\Permission;
use Botble\CustomField\Providers\CustomFieldServiceProvider;
use Schema;

class Plugin implements PluginInterface
{

    /**
     * @return array
     * @author Sang Nguyen
     */
    public static function permissions()
    {
        return [
            [
                'name' => 'Custom Fields',
                'flag' => 'custom-fields.list',
                'is_feature' => true
            ],
            [
                'name' => 'Create',
                'flag' => 'custom-fields.create',
                'parent_flag' => 'custom-fields.list'
            ],
            [
                'name' => 'Edit',
                'flag' => 'custom-fields.edit',
                'parent_flag' => 'custom-fields.list'
            ],
            [
                'name' => 'Delete',
                'flag' => 'custom-fields.delete',
                'parent_flag' => 'custom-fields.list'
            ]
        ];
    }

    /**
     * @author Sang Nguyen
     */
    public static function activate()
    {
        Permission::registerPermission(self::permissions());
        Artisan::call('migrate', [
            '--force' => true,
            '--path' => 'plugins/custom-field/database/migrations',
        ]);

        Artisan::call('vendor:publish', [
            '--force' => true,
            '--tag' => 'assets',
            '--provider' => CustomFieldServiceProvider::class,
        ]);
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

        Schema::dropIfExists('custom_fields');
        Schema::dropIfExists('field_items');
        Schema::dropIfExists('field_groups');
    }
}