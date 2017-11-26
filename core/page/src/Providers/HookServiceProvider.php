<?php

namespace Botble\Page\Providers;

use Illuminate\Support\ServiceProvider;
use Botble\Page\Repositories\Interfaces\PageInterface;
use Menu;

class HookServiceProvider extends ServiceProvider
{

    /**
     * Boot the service provider.
     * @author Sang Nguyen
     */
    public function boot()
    {
        add_action(MENU_ACTION_SIDEBAR_OPTIONS, [$this, 'registerMenuOptions'], 10);
        add_filter(DASHBOARD_FILTER_TOP_BLOCKS, [$this, 'addPageStatsWidget'], 15, 1);
        add_filter(MENU_FILTER_MENU_ITEM, [$this, 'addRelatedToMenuItem'], 10, 2);
    }

    /**
     * Register sidebar options in menu
     */
    public function registerMenuOptions()
    {
        $pages = Menu::generateSelect(['model' => app(PageInterface::class)->getModel(), 'theme' => false, 'options' => ['class' => 'list-item']]);
        echo view('pages::partials.menu-options', compact('pages'));
    }

    /**
     * @param $widgets
     * @return string
     * @author Sang Nguyen
     */
    public function addPageStatsWidget($widgets)
    {
        $pages = app(PageInterface::class)->count(['status' => 1]);

        return $widgets . view('pages::partials.widgets.stats', compact('pages'))->render();
    }

    /**
     * @param $item
     * @param $args
     * @return mixed
     */
    public function addRelatedToMenuItem($item, $args)
    {
        if ($args['type'] == 'pages') {
            $page = app(PageInterface::class)->getFirstBy(['id' => $args['related_id']]);
            if ($page) {
                if (trim($args['title']) == null) {
                    $item->name = $page->name;
                } else {
                    $item->name = $args['title'];
                }
                if ($args['theme']) {
                    $item->url = route('public.single.detail', $page->slug);
                } else {
                    $item->url = route('pages.edit', $page->id);
                }
            }
        }

        return $item;
    }
}
