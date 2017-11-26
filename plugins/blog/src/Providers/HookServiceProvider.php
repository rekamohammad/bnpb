<?php

namespace Botble\Blog\Providers;

use Illuminate\Support\ServiceProvider;
use Botble\Blog\Repositories\Interfaces\CategoryInterface;
use Botble\Blog\Repositories\Interfaces\TagInterface;
use Menu;
use Botble\Blog\Repositories\Interfaces\PostInterface;
use Botble\Dashboard\Repositories\Interfaces\DashboardWidgetInterface;
use Botble\Dashboard\Repositories\Interfaces\DashboardWidgetSettingInterface;

class HookServiceProvider extends ServiceProvider
{

    /**
     * Boot the service provider.
     * @author Sang Nguyen
     */
    public function boot()
    {
        add_action(MENU_ACTION_SIDEBAR_OPTIONS, [$this, 'registerMenuOptions'], 2);
        add_filter(DASHBOARD_FILTER_ADMIN_LIST, [$this, 'registerDashboardWidgets'], 21, 1);
        add_filter(DASHBOARD_FILTER_TOP_BLOCKS, [$this, 'addStatsWidgets'], 13, 1);
        add_filter(MENU_FILTER_MENU_ITEM, [$this, 'addRelatedToMenuItem'], 11, 2);
        add_action(BASE_ACTION_REGISTER_SITE_MAP, [$this, 'registerSiteMap'], 18, 1);
    }

    /**
     * Register sidebar options in menu
     */
    public function registerMenuOptions()
    {
        $categories = Menu::generateSelect([
            'model' => app(CategoryInterface::class)->getModel(),
            'theme' => false,
            'options' => [
                'class' => 'list-item',
            ]
        ]);
        echo view('blog::categories.partials.menu-options', compact('categories'));

        $tags = Menu::generateSelect([
            'model' => app(TagInterface::class)->getModel(),
            'theme' => false,
            'options' => [
                'class' => 'list-item',
            ]
        ]);
        echo view('blog::tags.partials.menu-options', compact('tags'));
    }

    /**
     * @param $widgets
     * @return array
     * @author Sang Nguyen
     */
    public function registerDashboardWidgets($widgets)
    {
        $widget = app(DashboardWidgetInterface::class)->firstOrCreate(['name' => 'widget_posts_recent']);
        $widget_setting = app(DashboardWidgetSettingInterface::class)->getFirstBy([
            'widget_id' => $widget->id,
            'user_id' => acl_get_current_user_id(),
        ], ['status']);

        if (empty($widget_setting) || array_key_exists($widget_setting->order, $widgets)) {
            $widgets[] = view('blog::posts.widgets.base', compact('widget', 'widget_setting'))->render();
        } else {
            $widgets[$widget_setting->order] = view('blog::posts.widgets.base', compact('widget', 'widget_setting'))->render();
        }
        return $widgets;
    }

    /**
     * @param $widgets
     * @return string
     * @author Sang Nguyen
     */
    public function addStatsWidgets($widgets)
    {
        $posts = app(PostInterface::class)->count(['status' => 1]);
        $categories = app(CategoryInterface::class)->count(['status' => 1]);

        $widgets = $widgets . view('blog::posts.widgets.stats', compact('posts'))->render();
        $widgets = $widgets . view('blog::categories.widgets.stats', compact('categories'))->render();

        return $widgets;
    }

    /**
     * @param $item
     * @param $args
     * @return mixed
     */
    public function addRelatedToMenuItem($item, $args)
    {
        if ($args['type'] == 'categories') {
            $category = app(CategoryInterface::class)->getFirstBy(['id' => $args['related_id']]);
            if ($category) {
                if (trim($args['title']) == null) {
                    $item->name = $category->name;
                } else {
                    $item->name = $args['title'];
                }
                if ($args['theme']) {
                    $item->url = route('public.single.detail', $category->slug);
                } else {
                    $item->url = route('categories.edit', $category->id);
                }
            }
        }

        if ($args['type'] == 'tags') {
            $tag = app(TagInterface::class)->getFirstBy(['id' => $args['related_id']]);
            if ($tag) {
                if (trim($args['title']) == null) {
                    $item->name = $tag->name;
                } else {
                    $item->name = $args['title'];
                }
                if ($args['theme']) {
                    $item->url = route('public.tag', $tag->slug);
                } else {
                    $item->url = route('tags.edit', $tag->id);
                }
            }
        }

        return $item;
    }

    /**
     * @param $site_map
     * @return void
     * @author Sang Nguyen
     */
    public function registerSiteMap($site_map)
    {
        // get all posts from db
        $posts = $this->app->make(PostInterface::class)->getDataSiteMap();

        // add every post to the site map
        foreach ($posts as $post) {
            $site_map->add(route('public.single.detail', $post->slug), $post->updated_at, '0.8', 'daily');
        }

        // get all categories from db
        $categories = $this->app->make(CategoryInterface::class)->getDataSiteMap();

        // add every category to the site map
        foreach ($categories as $category) {
            $site_map->add(route('public.single.detail', $category->slug), $category->updated_at, '0.8', 'daily');
        }

        // get all tags from db
        $tags = $this->app->make(TagInterface::class)->getDataSiteMap();

        // add every tag to the site map
        foreach ($tags as $tag) {
            $site_map->add(route('public.tag', $tag->slug), $tag->updated_at, '0.3', 'weekly');
        }
    }
}
