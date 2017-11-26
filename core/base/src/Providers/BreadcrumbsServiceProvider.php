<?php

namespace Botble\Base\Providers;

use Breadcrumbs;
use DaveJamesMiller\Breadcrumbs\Generator;
use Illuminate\Support\ServiceProvider;

class BreadcrumbsServiceProvider extends ServiceProvider
{
    /**
     * @author Sang Nguyen
     */
    public function boot()
    {

        Breadcrumbs::register('', function (Generator $breadcrumbs) {
            $breadcrumbs->push('', '');
        });

        Breadcrumbs::register('dashboard.index', function (Generator $breadcrumbs) {
            $breadcrumbs->push(trans('bases::layouts.dashboard'), route('dashboard.index'));
        });

        /**
         * Register breadcrumbs based on menu stored in session
         * @author Sang Nguyen
         */
        Breadcrumbs::register('pageTitle', function (Generator $breadcrumbs, $defaultTitle = 'pageTitle', $url) {

            $arMenu = dashboard_menu()->getAll();
            $breadcrumbs->parent('dashboard.index');
            $found = false;
            foreach ($arMenu as $menuCategory) {
                if ($url == $menuCategory->url && !empty($menuCategory->name)) {
                    $found = true;
                    $breadcrumbs->push($menuCategory->name, $url);
                    break;
                }
            }
            if (!$found) {
                foreach ($arMenu as $menuCategory) {
                    if (isset($menuCategory->children)) {
                        foreach ($menuCategory->children as $menuItem) {
                            if ($url == $menuItem->url && !empty($menuItem->name)) {
                                $found = true;
                                $breadcrumbs->push($menuCategory->name, $menuCategory->url);
                                $breadcrumbs->push($menuItem->name, $url);
                                break;
                            }
                        }
                    }
                }
            }

            if (!$found) {
                $breadcrumbs->push($defaultTitle, $url);
            }
        });

        do_action(BASE_ACTION_REGISTER_BREADCRUMBS);
    }
}
