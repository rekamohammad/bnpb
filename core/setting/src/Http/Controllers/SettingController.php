<?php

namespace Botble\Setting\Http\Controllers;

use Botble\Base\Http\Controllers\BaseController;
use Illuminate\Http\Request;
use Setting;

class SettingController extends BaseController
{
    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @author Sang Nguyen
     */
    public function getOptions()
    {
        page_title()->setTitle(trans('settings::setting.title'));

        $settings = Setting::getConfig();
        return view('settings::index', compact('settings'));
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     * @author Sang Nguyen
     */
    public function postEdit(Request $request)
    {
        $settings = Setting::getConfig();

        foreach ($settings as $tab) {
            foreach ($tab['settings'] as $setting) {
                $key = $setting['attributes']['name'];
                Setting::set($key, $request->input($key, 0));
            }
        }

        Setting::save();
        if ($request->input('submit') === 'save') {
            return redirect()->route('settings.options')->with('success_msg', trans('bases::notices.update_success_message'));
        } else {
            return redirect()->back()->with('success_msg', trans('bases::notices.update_success_message'));
        }
    }
}
