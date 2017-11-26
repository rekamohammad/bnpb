<?php

namespace Botble\Analytics\Providers;

use Assets;
use Illuminate\Support\ServiceProvider;
use Botble\Dashboard\Repositories\Interfaces\DashboardWidgetInterface;
use Botble\Dashboard\Repositories\Interfaces\DashboardWidgetSettingInterface;

class HookServiceProvider extends ServiceProvider
{

    /**
     * Bootstrap the application events.
     * @author Sang Nguyen
     */
    public function boot()
    {
        add_action(DASHBOARD_ACTION_REGISTER_SCRIPTS, [$this, 'registerScripts'], 18);
        add_filter(DASHBOARD_FILTER_ADMIN_LIST, [$this, 'addGeneralWidget'], 18, 1);
        add_filter(DASHBOARD_FILTER_ADMIN_LIST, [$this, 'addPageWidget'], 19, 1);
        add_filter(DASHBOARD_FILTER_ADMIN_LIST, [$this, 'addBrowserWidget'], 20, 1);
        add_filter(DASHBOARD_FILTER_ADMIN_LIST, [$this, 'addReferrerWidget'], 22, 1);
    }

    /**
     * @return void
     * @author Sang Nguyen
     */
    public function registerScripts()
    {
        Assets::addJavascript(['jvectormap', 'morris']);
        Assets::addStylesheets(['jvectormap', 'morris']);
    }

    /**
     * @param $widgets
     * @return string
     * @author Sang Nguyen
     */
    public function addGeneralWidget($widgets)
    {
        $widget = app(DashboardWidgetInterface::class)->firstOrCreate(['name' => 'widget_analytics_general']);
        $widget_setting = app(DashboardWidgetSettingInterface::class)->getFirstBy([
            'widget_id' => $widget->id,
            'user_id' => acl_get_current_user_id(),
        ], ['status']);

        if (empty($widget_setting) || array_key_exists($widget_setting->order, $widgets)) {
            $widgets[] = view('analytics::widgets.general.base', compact('widget', 'widget_setting'))->render();
        } else {
            $widgets[$widget_setting->order] = view('analytics::widgets.general.base', compact('widget', 'widget_setting'))->render();
        }
        return $widgets;
    }

    /**
     * @param $widgets
     * @return string
     * @author Sang Nguyen
     */
    public function addPageWidget($widgets)
    {
        $widget = app(DashboardWidgetInterface::class)->firstOrCreate(['name' => 'widget_analytics_page']);
        $widget_setting =app(DashboardWidgetSettingInterface::class)->getFirstBy([
            'widget_id' => $widget->id,
            'user_id' => acl_get_current_user_id(),
        ], ['status']);

        if (empty($widget_setting) || array_key_exists($widget_setting->order, $widgets)) {
            $widgets[] = view('analytics::widgets.page.base', compact('widget', 'widget_setting'))->render();
        } else {
            $widgets[$widget_setting->order] = view('analytics::widgets.page.base', compact('widget', 'widget_setting'))->render();
        }
        return $widgets;
    }

    /**
     * @param $widgets
     * @return string
     * @author Sang Nguyen
     */
    public function addBrowserWidget($widgets)
    {
        $widget = app(DashboardWidgetInterface::class)->firstOrCreate(['name' => 'widget_analytics_browser']);
        $widget_setting =app(DashboardWidgetSettingInterface::class)->getFirstBy([
            'widget_id' => $widget->id,
            'user_id' => acl_get_current_user_id(),
        ], ['status']);

        if (empty($widget_setting) || array_key_exists($widget_setting->order, $widgets)) {
            $widgets[] = view('analytics::widgets.browser.base', compact('widget', 'widget_setting'))->render();
        } else {
            $widgets[$widget_setting->order] = view('analytics::widgets.browser.base', compact('widget', 'widget_setting'))->render();
        }
        return $widgets;
    }

    /**
     * @param $widgets
     * @return string
     * @author Sang Nguyen
     */
    public function addReferrerWidget($widgets)
    {
        $widget = app(DashboardWidgetInterface::class)->firstOrCreate(['name' => 'widget_analytics_referrer']);
        $widget_setting =app(DashboardWidgetSettingInterface::class)->getFirstBy([
            'widget_id' => $widget->id,
            'user_id' => acl_get_current_user_id(),
        ], ['status']);

        if (empty($widget_setting) || array_key_exists($widget_setting->order, $widgets)) {
            $widgets[] = view('analytics::widgets.referrer.base', compact('widget', 'widget_setting'))->render();
        } else {
            $widgets[$widget_setting->order] = view('analytics::widgets.referrer.base', compact('widget', 'widget_setting'))->render();
        }
        return $widgets;
    }
}
