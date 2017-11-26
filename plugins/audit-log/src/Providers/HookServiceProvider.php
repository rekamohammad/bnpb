<?php

namespace Botble\AuditLog\Providers;

use Illuminate\Support\ServiceProvider;
use Botble\ACL\Models\User;
use Botble\AuditLog\Events\AuditHandlerEvent;
use Illuminate\Http\Request;
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
        add_action(BASE_ACTION_AFTER_CREATE_CONTENT, [$this, 'handleCreated'], 45, 3);
        add_action(BASE_ACTION_AFTER_UPDATE_CONTENT, [$this, 'handleUpdated'], 45, 3);
        add_action(BASE_ACTION_AFTER_DELETE_CONTENT, [$this, 'handleDeleted'], 45, 3);

        add_action(AUTH_ACTION_AFTER_LOGIN_SYSTEM, [$this, 'handleLogin'], 45, 3);
        add_action(AUTH_ACTION_AFTER_LOGOUT_SYSTEM, [$this, 'handleLogout'], 45, 3);

        add_action(USER_ACTION_AFTER_UPDATE_PASSWORD, [$this, 'handleUpdatePassword'], 45, 3);
        add_action(USER_ACTION_AFTER_UPDATE_PASSWORD, [$this, 'handleUpdateProfile'], 45, 3);

        if (defined('BACKUP_ACTION_AFTER_BACKUP')) {
            add_action(BACKUP_ACTION_AFTER_BACKUP, [$this, 'handleBackup'], 45, 2);
            add_action(BACKUP_ACTION_AFTER_RESTORE, [$this, 'handleRestore'], 45, 2);
        }

        add_filter(DASHBOARD_FILTER_ADMIN_LIST, [$this, 'registerDashboardWidgets'], 28, 1);
    }

    /**
     * @param $screen
     * @param Request $request
     * @param $data
     * @return string
     * @author Sang Nguyen
     */
    protected function getReferenceName($screen, $request, $data)
    {
        $name = null;
        switch ($screen) {
            case USER_MODULE_SCREEN_NAME:
            case AUTH_MODULE_SCREEN_NAME:
                /**
                 * @var User $data
                 */
                $name = $data->getFullName();
                break;
            default:
                if (!empty($data)) {
                    if (isset($data->name)) {
                        $name = $data->name;
                    } elseif (isset($data->title)) {
                        $name = $data->title;
                    }
                }
        }
        return $name;
    }

    /**
     * @param $screen
     * @param Request $request
     * @param $data
     * @author Sang Nguyen
     */
    public function handleCreated($screen, Request $request, $data)
    {
        if (!$data->id) {
            return false;
        }
        event(new AuditHandlerEvent($screen, 'created', $data->id, self::getReferenceName($screen, $request, $data), 'info'));
    }

    /**
     * @param $screen
     * @param Request $request
     * @param $data
     * @author Sang Nguyen
     */
    public function handleUpdated($screen, Request $request, $data)
    {
        if (!$data->id) {
            return false;
        }
        event(new AuditHandlerEvent($screen, 'updated', $data->id, self::getReferenceName($screen, $request, $data), 'primary'));
    }

    /**
     * @param $screen
     * @param Request $request
     * @param $data
     * @author Sang Nguyen
     */
    public function handleDeleted($screen, Request $request, $data)
    {
        if (!$data->id) {
            return false;
        }
        event(new AuditHandlerEvent($screen, 'deleted', $data->id, self::getReferenceName($screen, $request, $data), 'danger'));
    }

    /**
     * @param $screen
     * @param Request $request
     * @param $data
     * @author Sang Nguyen
     */
    public function handleLogin($screen, Request $request, $data)
    {
        event(new AuditHandlerEvent('to the system', 'logged in', $data->id, self::getReferenceName($screen, $request, $data), 'info'));
    }

    /**
     * @param $screen
     * @param Request $request
     * @param $data
     * @author Sang Nguyen
     */
    public function handleLogout($screen, Request $request, $data)
    {
        event(new AuditHandlerEvent('of the system', 'logged out', $data->id, self::getReferenceName($screen, $request, $data), 'info'));
    }

    /**
     * @param $screen
     * @param Request $request
     * @param $data
     * @author Sang Nguyen
     */
    public function handleUpdateProfile($screen, Request $request, $data)
    {
        event(new AuditHandlerEvent($screen, 'updated profile', $data->id, self::getReferenceName($screen, $request, $data), 'info'));
    }

    /**
     * @param $screen
     * @param Request $request
     * @param $data
     * @author Sang Nguyen
     */
    public function handleUpdatePassword($screen, Request $request, $data)
    {
        event(new AuditHandlerEvent($screen, 'changed password', $data->id, self::getReferenceName($screen, $request, $data), 'danger'));
    }

    /**
     * @param $screen
     * @param Request $request
     * @author Sang Nguyen
     */
    public function handleBackup($screen, Request $request)
    {
        event(new AuditHandlerEvent($screen, 'backup', 0, '', 'info'));
    }

    /**
     * @param $screen
     * @param Request $request
     * @author Sang Nguyen
     */
    public function handleRestore($screen, Request $request)
    {
        event(new AuditHandlerEvent($screen, 'restored', 0, '', 'info'));
    }

    /**
     * @param $widgets
     * @return string
     * @author Sang Nguyen
     */
    public function registerDashboardWidgets($widgets)
    {
        $widget = app(DashboardWidgetInterface::class)->firstOrCreate(['name' => 'widget_audit_logs'], ['id', 'name']);
        $widget_setting = app(DashboardWidgetSettingInterface::class)->getFirstBy(['widget_id' => $widget->id, 'user_id' => acl_get_current_user_id()], ['status']);

        if (empty($widget_setting) || array_key_exists($widget_setting->order, $widgets)) {
            $widgets[] = view('audit-logs::widgets.base', compact('widget', 'widget_setting'))->render();
        } else {
            $widgets[$widget_setting->order] = view('audit-logs::widgets.base', compact('widget', 'widget_setting'))->render();
        }
        return $widgets;
    }
}
