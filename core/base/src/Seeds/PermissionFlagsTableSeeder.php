<?php

namespace Botble\Base\Seeds;

use Botble\Base\Supports\Commands\Permission;
use Illuminate\Database\Seeder;

class PermissionFlagsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Permission::registerPermission($this->permissions());
    }

    protected function permissions()
    {
        return [
            [
                'name' => 'Dashboard',
                'flag' => 'dashboard.index',
                'is_feature' => true,
            ],

            [
                'name' => 'Page',
                'flag' => 'pages.list',
                'is_feature' => true,
            ],
            [
                'name' => 'Create',
                'flag' => 'pages.create',
                'parent_flag' => 'pages.list',
            ],
            [
                'name' => 'Edit',
                'flag' => 'pages.edit',
                'parent_flag' => 'pages.list',
            ],
            [
                'name' => 'Delete',
                'flag' => 'pages.delete',
                'parent_flag' => 'pages.list',
            ],

            [
                'name' => 'Media',
                'flag' => 'media.index',
                'is_feature' => true,
            ],
            [
                'name' => 'File',
                'flag' => 'files.list',
                'is_feature' => true,
                'parent_flag' => 'media.index',
            ],
            [
                'name' => 'Create',
                'flag' => 'files.create',
                'parent_flag' => 'files.list',
            ],
            [
                'name' => 'Edit',
                'flag' => 'files.edit',
                'parent_flag' => 'files.list',
            ],
            [
                'name' => 'Trash',
                'flag' => 'files.trash',
                'parent_flag' => 'files.list',
            ],
            [
                'name' => 'Delete',
                'flag' => 'files.delete',
                'parent_flag' => 'files.list',
            ],

            [
                'name' => 'Folder',
                'flag' => 'folders.list',
                'is_feature' => true,
                'parent_flag' => 'media.index',
            ],
            [
                'name' => 'Create',
                'flag' => 'folders.create',
                'parent_flag' => 'folders.list',
            ],
            [
                'name' => 'Edit',
                'flag' => 'folders.edit',
                'parent_flag' => 'folders.list',
            ],
            [
                'name' => 'Trash',
                'flag' => 'folders.trash',
                'parent_flag' => 'folders.list',
            ],
            [
                'name' => 'Delete',
                'flag' => 'folders.delete',
                'parent_flag' => 'folders.list',
            ],

            [
                'name' => 'Settings',
                'flag' => 'settings.options',
                'is_feature' => true,
            ],

            [
                'name' => 'Users',
                'flag' => 'users.list',
                'is_feature' => true,
            ],
            [
                'name' => 'Create',
                'flag' => 'users.create',
                'parent_flag' => 'users.list',
            ],
            [
                'name' => 'Edit',
                'flag' => 'users.edit',
                'parent_flag' => 'users.list',
            ],
            [
                'name' => 'Delete',
                'flag' => 'users.delete',
                'parent_flag' => 'users.list',
            ],

            [
                'name' => 'Roles',
                'flag' => 'roles.list',
                'is_feature' => true,
            ],
            [
                'name' => 'Create',
                'flag' => 'roles.create',
                'parent_flag' => 'roles.list',
            ],
            [
                'name' => 'Edit',
                'flag' => 'roles.edit',
                'parent_flag' => 'roles.list',
            ],
            [
                'name' => 'Delete',
                'flag' => 'roles.delete',
                'parent_flag' => 'roles.list',
            ],

            [
                'name' => 'Menu',
                'flag' => 'menus.list',
                'is_feature' => true,
            ],
            [
                'name' => 'Create',
                'flag' => 'menus.create',
                'parent_flag' => 'menus.list',
            ],
            [
                'name' => 'Edit',
                'flag' => 'menus.edit',
                'parent_flag' => 'menus.list',
            ],
            [
                'name' => 'Delete',
                'flag' => 'menus.delete',
                'parent_flag' => 'menus.list',
            ],

            [
                'name' => 'Widgets',
                'flag' => 'widgets.list',
                'is_feature' => true,
            ],

            [
                'name' => 'Plugins',
                'flag' => 'plugins.list',
                'is_feature' => true,
            ],

            [
                'name' => 'Theme',
                'flag' => 'theme.list',
                'is_feature' => true,
            ],
            [
                'name' => 'All themes',
                'flag' => 'theme.list',
                'parent_flag' => 'theme.list',
            ],
            [
                'name' => 'Theme options',
                'flag' => 'theme.options',
                'parent_flag' => 'theme.list',
            ],
        ];
    }
}
