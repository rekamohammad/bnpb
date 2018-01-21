<?php

namespace Botble\Blog;

use Artisan;
use Botble\Base\Supports\Commands\Permission;
use Botble\Blog\Providers\BlogServiceProvider;
use Schema;
use Botble\Base\Interfaces\PluginInterface;

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
                'name' => 'Posts',
                'flag' => 'posts.list',
                'is_feature' => true,
            ],
            [
                'name' => 'Create',
                'flag' => 'posts.create',
                'parent_flag' => 'posts.list',
            ],
            [
                'name' => 'Edit',
                'flag' => 'posts.edit',
                'parent_flag' => 'posts.list',
            ],
            [
                'name' => 'Delete',
                'flag' => 'posts.delete',
                'parent_flag' => 'posts.list',
            ],

            [
                'name' => 'News',
                'flag' => 'news.list',
                'is_feature' => true,
            ],
            [
                'name' => 'Create',
                'flag' => 'news.create',
                'parent_flag' => 'news.list',
            ],
            [
                'name' => 'Edit',
                'flag' => 'news.edit',
                'parent_flag' => 'news.list',
            ],
            [
                'name' => 'Delete',
                'flag' => 'news.delete',
                'parent_flag' => 'news.list',
            ],

            [
                'name' => 'Album',
                'flag' => 'album.list',
                'is_feature' => true,
            ],
            [
                'name' => 'Create',
                'flag' => 'album.create',
                'parent_flag' => 'album.list',
            ],
            [
                'name' => 'Edit',
                'flag' => 'album.edit',
                'parent_flag' => 'album.list',
            ],
            [
                'name' => 'Delete',
                'flag' => 'album.delete',
                'parent_flag' => 'album.list',
            ],
			
			[
                'name' => 'Nasional',
                'flag' => 'nasional.list',
                'is_feature' => true,
            ],
            [
                'name' => 'Create',
                'flag' => 'nasional.create',
                'parent_flag' => 'nasional.list',
            ],
            [
                'name' => 'Edit',
                'flag' => 'nasional.edit',
                'parent_flag' => 'nasional.list',
            ],
            [
                'name' => 'Delete',
                'flag' => 'nasional.delete',
                'parent_flag' => 'nasional.list',
            ],
			
			//International
			[
                'name' => 'Internasional',
                'flag' => 'internasional.list',
                'is_feature' => true,
            ],
            [
                'name' => 'Create',
                'flag' => 'internasional.create',
                'parent_flag' => 'internasional.list',
            ],
            [
                'name' => 'Edit',
                'flag' => 'internasional.edit',
                'parent_flag' => 'internasional.list',
            ],
            [
                'name' => 'Delete',
                'flag' => 'internasional.delete',
                'parent_flag' => 'internasional.list',
            ],
			
			//Provinsi
			[
                'name' => 'Provinsi',
                'flag' => 'provinsi.list',
                'is_feature' => true,
            ],
            [
                'name' => 'Create',
                'flag' => 'provinsi.create',
                'parent_flag' => 'provinsi.list',
            ],
            [
                'name' => 'Edit',
                'flag' => 'provinsi.edit',
                'parent_flag' => 'provinsi.list',
            ],
            [
                'name' => 'Delete',
                'flag' => 'provinsi.delete',
                'parent_flag' => 'provinsi.list',
            ],
			

            [
                'name' => 'Diorama',
                'flag' => 'diorama.list',
                'is_feature' => true,
            ],
            [
                'name' => 'Create',
                'flag' => 'diorama.create',
                'parent_flag' => 'diorama.list',
            ],
            [
                'name' => 'Edit',
                'flag' => 'diorama.edit',
                'parent_flag' => 'diorama.list',
            ],
            [
                'name' => 'Delete',
                'flag' => 'diorama.delete',
                'parent_flag' => 'diorama.list',
            ],

            [
                'name' => 'Publikasi',
                'flag' => 'publikasi.list',
                'is_feature' => true,
            ],
            [
                'name' => 'Create',
                'flag' => 'publikasi.create',
                'parent_flag' => 'publikasi.list',
            ],
            [
                'name' => 'Edit',
                'flag' => 'publikasi.edit',
                'parent_flag' => 'publikasi.list',
            ],
            [
                'name' => 'Delete',
                'flag' => 'publikasi.delete',
                'parent_flag' => 'publikasi.list',
            ],

            [
                'name' => 'Infografis',
                'flag' => 'infografis.list',
                'is_feature' => true,
            ],
            [
                'name' => 'Create',
                'flag' => 'infografis.create',
                'parent_flag' => 'infografis.list',
            ],
            [
                'name' => 'Edit',
                'flag' => 'infografis.edit',
                'parent_flag' => 'infografis.list',
            ],
            [
                'name' => 'Delete',
                'flag' => 'infografis.delete',
                'parent_flag' => 'infografis.list',
            ],

            [
                'name' => 'Categories',
                'flag' => 'categories.list',
                'is_feature' => true,
            ],
            [
                'name' => 'Create',
                'flag' => 'categories.create',
                'parent_flag' => 'categories.list',
            ],
            [
                'name' => 'Edit',
                'flag' => 'categories.edit',
                'parent_flag' => 'categories.list',
            ],
            [
                'name' => 'Delete',
                'flag' => 'categories.delete',
                'parent_flag' => 'categories.list',
            ],

            [
                'name' => 'Tags',
                'flag' => 'tags.list',
                'is_feature' => true,
            ],
            [
                'name' => 'Create',
                'flag' => 'tags.create',
                'parent_flag' => 'tags.list',
            ],
            [
                'name' => 'Edit',
                'flag' => 'tags.edit',
                'parent_flag' => 'tags.list',
            ],
            [
                'name' => 'Delete',
                'flag' => 'tags.delete',
                'parent_flag' => 'tags.list',
            ],
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
            '--path' => 'plugins/blog/database/migrations',
        ]);

        Artisan::call('vendor:publish', [
            '--force' => true,
            '--tag' => 'assets',
            '--provider' => BlogServiceProvider::class,
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
        Schema::dropIfExists('posts');
        Schema::dropIfExists('categories');
        Schema::dropIfExists('tags');
        Schema::dropIfExists('news');
    }
}