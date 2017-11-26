<?php

namespace Botble\RequestLog\Providers;

use Botble\RequestLog\Events\RequestHandlerEvent;
use Illuminate\Support\ServiceProvider;
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
        add_action(BASE_ACTION_SITE_ERROR, [$this, 'handleSiteError'], 125, 1);
        add_filter(DASHBOARD_FILTER_ADMIN_LIST, [$this, 'registerDashboardWidgets'], 125, 1);
    }

    /**
     * Fire event log
     *
     * @param $code
     * @author Sang Nguyen
     */
    public function handleSiteError($code)
    {
        event(new RequestHandlerEvent($code));
    }

    /**
     * @param $widgets
     * @return string
     * @author Sang Nguyen
     */
    public function registerDashboardWidgets($widgets)
    {
        $widget = app(DashboardWidgetInterface::class)->firstOrCreate(['name' => 'widget_request_errors']);
        $widget_setting = app(DashboardWidgetSettingInterface::class)->getFirstBy([
            'widget_id' => $widget->id,
            'user_id' => acl_get_current_user_id(),
        ], ['status']);

        if (empty($widget_setting) || array_key_exists($widget_setting->order, $widgets)) {
            $widgets[] = view('request-logs::widgets.base', compact('widget', 'widget_setting'))->render();
        } else {
            $widgets[$widget_setting->order] = view('request-logs::widgets.base', compact('widget', 'widget_setting'))->render();
        }
        return $widgets;
    }
}
