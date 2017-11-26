<?php
namespace Botble\Backup;

use Artisan;
use Botble\Backup\Providers\BackupServiceProvider;
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
        return [
            [
                'name' => 'Backup',
                'flag' => 'backups.list',
                'is_feature' => true,
            ],
            [
                'name' => 'Create',
                'flag' => 'backups.create',
                'parent_flag' => 'backups.list',
            ],
            [
                'name' => 'Restore',
                'flag' => 'backups.restore',
                'parent_flag' => 'backups.list',
            ],
            [
                'name' => 'Delete',
                'flag' => 'backups.delete',
                'parent_flag' => 'backups.list',
            ]
        ];
    }

    /**
     * @author Sang Nguyen
     */
    public static function activate()
    {
        Permission::registerPermission(self::permissions());

        Artisan::call('vendor:publish', [
            '--force' => true,
            '--tag' => 'assets',
            '--provider' => BackupServiceProvider::class,
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
    }
}