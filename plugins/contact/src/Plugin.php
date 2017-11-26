<?php

namespace Botble\Contact;

use Artisan;
use Botble\Base\Interfaces\PluginInterface;
use Botble\Base\Supports\Commands\Permission;
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
                'name' => 'Contacts',
                'flag' => 'contacts.list',
                'is_feature' => true,
            ],
            [
                'name' => 'Create',
                'flag' => 'contacts.create',
                'parent_flag' => 'contacts.list',
            ],
            [
                'name' => 'Edit',
                'flag' => 'contacts.edit',
                'parent_flag' => 'contacts.list',
            ],
            [
                'name' => 'Delete',
                'flag' => 'contacts.delete',
                'parent_flag' => 'contacts.list',
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
            '--path' => 'plugins/contact/database/migrations',
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
        Schema::dropIfExists('contacts');
    }
}