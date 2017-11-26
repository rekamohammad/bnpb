<?php

namespace Botble\Gallery;

use Artisan;
use Botble\Base\Interfaces\PluginInterface;
use Botble\Base\Supports\Commands\Permission;
use Botble\Gallery\Providers\GalleryServiceProvider;
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
                'name' => 'Galleries',
                'flag' => 'galleries.list',
                'is_feature' => true,
            ],
            [
                'name' => 'Create',
                'flag' => 'galleries.create',
                'parent_flag' => 'galleries.list',
            ],
            [
                'name' => 'Edit',
                'flag' => 'galleries.edit',
                'parent_flag' => 'galleries.list',
            ],
            [
                'name' => 'Delete',
                'flag' => 'galleries.delete',
                'parent_flag' => 'galleries.list',
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
            '--path' => 'plugins/gallery/database/migrations',
        ]);

        Artisan::call('vendor:publish', [
            '--force' => true,
            '--tag' => 'assets',
            '--provider' => GalleryServiceProvider::class,
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

        Schema::dropIfExists('galleries');
        Schema::dropIfExists('gallery_meta');
    }
}