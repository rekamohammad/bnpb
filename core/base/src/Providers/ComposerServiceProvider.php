<?php

namespace Botble\Base\Providers;

use Assets;
use Botble\ACL\Models\UserMeta;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Support\ServiceProvider;
use RvMedia;

class ComposerServiceProvider extends ServiceProvider
{

    /**
     * @author Sang Nguyen
     * @param Factory $view
     */
    public function boot(Factory $view)
    {
        $view->composer(['bases::layouts.partials.top-header', 'bases::layouts.base'], function (View $view) {
            $themes = Assets::getThemes();
            $locales = Assets::getAdminLocales();

            if (acl_check_login()) {
                $active_theme = UserMeta::getMeta('admin-theme', config('cms.default-theme'));
            } elseif (session()->has('admin-theme')) {
                $active_theme = session('admin-theme');
            } else {
                $active_theme = config('cms.default-theme');
            }

            if (!array_key_exists($active_theme, $themes)) {
                $active_theme = config('cms.default-theme');
            }

            $view->with(compact('themes', 'locales', 'active_theme'));
        });

        $view->composer(['bases::layouts.base'], function (View $view) {

            do_action(BASE_ACTION_ENQUEUE_SCRIPTS);

            $headScripts = Assets::getJavascript('top');
            $bodyScripts = Assets::getJavascript('bottom');
            $appModules = Assets::getAppModules();

            $groupedBodyScripts = array_merge($bodyScripts, $appModules);

            $view->with('headScripts', $headScripts);
            $view->with('bodyScripts', $groupedBodyScripts);
            $view->with('stylesheets', Assets::getStylesheets(['core']));
        });

        $view->composer(['media::config'], function (View $view) {
            $mediaPermissions = config('media.permissions');
            if (!acl_get_current_user()->isSuperUser()) {
                $mediaPermissions = array_intersect(array_keys(acl_get_current_user()->permissions), config('media.permissions'));
            }
            RvMedia::setPermissions($mediaPermissions);
        });
    }
}
