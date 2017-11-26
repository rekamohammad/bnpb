<?php

namespace Botble\Dashboard\Http\Controllers;

use Assets;
use Botble\ACL\Repositories\Interfaces\UserInterface;
use Botble\Base\Http\Controllers\BaseController;
use Botble\Dashboard\Repositories\Interfaces\DashboardWidgetInterface;
use Botble\Dashboard\Repositories\Interfaces\DashboardWidgetSettingInterface;
use Exception;
use Illuminate\Http\Request;

class DashboardController extends BaseController
{

    /**
     * @var DashboardWidgetSettingInterface
     */
    protected $widgetSettingRepository;

    /**
     * @var DashboardWidgetInterface
     */
    protected $widgetRepository;

    /**
     * @var UserInterface
     */
    protected $userRepository;

    /**
     * DashboardController constructor.
     * @param DashboardWidgetSettingInterface $widgetSettingRepository
     * @param DashboardWidgetInterface $widgetRepository
     * @param UserInterface $userRepository
     */
    public function __construct(
        DashboardWidgetSettingInterface $widgetSettingRepository,
        DashboardWidgetInterface $widgetRepository,
        UserInterface $userRepository
    )
    {
        $this->widgetSettingRepository = $widgetSettingRepository;
        $this->widgetRepository = $widgetRepository;
        $this->userRepository = $userRepository;
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @author Sang Nguyen
     */
    public function getDashboard()
    {
        page_title()->setTitle(trans('dashboard::dashboard.title'));

        Assets::addJavascript(['blockui', 'sortable', 'equal-height', 'counterup']);
        Assets::addAppModule(['dashboard']);

        do_action(DASHBOARD_ACTION_REGISTER_SCRIPTS);

        $widgets = $this->widgetRepository->advancedGet([
            'with' => ['userSetting'],
            'select' => ['id', 'name'],
        ]);

        $user_widgets = apply_filters(DASHBOARD_FILTER_ADMIN_LIST, []);
        ksort($user_widgets);

        return view('dashboard::list', compact('widgets', 'user_widgets'));
    }

    /**
     * @param Request $request
     * @return mixed
     * @author Sang Nguyen
     */
    public function postEditWidgetSettings(Request $request)
    {
        try {
            $widget = $this->widgetSettingRepository->findById($request->input('id'));
            $widget->settings = $request->input('settings');
            $this->widgetSettingRepository->createOrUpdate($widget);
            return [
                'error' => false,
                'message' => trans('dashboard::dashboard.save_setting_success'),
            ];
        } catch (Exception $ex) {
            return [
                'error' => true,
                'message' => $ex->getMessage(),
            ];
        }
    }

    /**
     * @param Request $request
     * @return mixed
     * @author Sang Nguyen
     */
    public function postEditWidgetSettingItem(Request $request)
    {
        try {
            $widget = $this->widgetRepository->getFirstBy([
                'name' => $request->input('name'),
            ]);

            if (!$widget) {
                return [
                    'error' => true,
                    'message' => trans('dashboard::dashboard.widget_not_exists'),
                ];
            }
            $widget_setting = $this->widgetSettingRepository->firstOrCreate(['widget_id' => $widget->id, 'user_id' => acl_get_current_user_id()]);
            $widget_setting->settings = array_merge((array)$widget_setting->settings, [$request->input('setting_name') => $request->input('setting_value')]);
            $this->widgetSettingRepository->createOrUpdate($widget_setting);
        } catch (Exception $ex) {
            return [
                'error' => true,
                'message' => $ex->getMessage(),
            ];
        }
        return [
            'error' => false,
        ];
    }

    /**
     * @param Request $request
     * @return array
     * @author Sang Nguyen
     */
    public function postUpdateWidgetOrder(Request $request)
    {
        foreach ($request->input('items') as $key => $item) {
            $widget = $this->widgetRepository->firstOrCreate([
                'name' => $item,
            ]);
            $widget_setting = $this->widgetSettingRepository->firstOrCreate([
                'widget_id' => $widget->id,
                'user_id' => acl_get_current_user_id(),
            ]);
            $widget_setting->order = $key;
            $this->widgetSettingRepository->createOrUpdate($widget_setting);
        }
        return [
            'error' => false,
            'message' => trans('dashboard::dashboard.update_position_success'),
        ];
    }

    /**
     * @param Request $request
     * @return array
     * @author Sang Nguyen
     */
    public function getHideWidget(Request $request)
    {
        $widget = $this->widgetRepository->getFirstBy([
            'name' => $request->input('name'),
        ], ['id']);
        if (!empty($widget)) {
            $widget_setting = $this->widgetSettingRepository->firstOrCreate([
                'widget_id' => $widget->id,
                'user_id' => acl_get_current_user_id(),
            ]);
            $widget_setting->status = 0;
            $this->widgetRepository->createOrUpdate($widget_setting);
        }
        return [
            'error' => false,
            'message' => trans('dashboard::dashboard.hide_success'),
        ];
    }

    /**
     * @param Request $request
     * @return array
     * @author Sang Nguyen
     */
    public function postHideWidgets(Request $request)
    {
        $widgets = $this->widgetRepository->all();
        foreach ($widgets as $widget) {
            $widget_setting = $this->widgetSettingRepository->firstOrCreate([
                'widget_id' => $widget->id,
                'user_id' => acl_get_current_user_id(),
            ]);
            if (array_key_exists($widget->name, $request->input('widgets', []))) {
                $widget_setting->status = 1;
                $this->widgetRepository->createOrUpdate($widget_setting);
            } else {
                $widget_setting->status = 0;
                $this->widgetRepository->createOrUpdate($widget_setting);
            }
        }
        return redirect()->route('dashboard.index')->with('success_msg', trans('dashboard::dashboard.hide_success'));
    }
}
