<?php

namespace Botble\Theme\Http\Controllers;

use Artisan;
use Assets;
use Botble\Base\Http\Controllers\BaseController;
use ThemeOption;
use Illuminate\Http\Request;
use Setting;

class ThemeController extends BaseController
{
    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @author Sang Nguyen
     */
    public function getList()
    {
        page_title()->setTitle(trans('theme::theme.theme'));

        return view('theme::list');
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @author Sang Nguyen
     */
    public function getOptions()
    {
        page_title()->setTitle(trans('theme::theme.theme_options'));

        Assets::addJavascript(['bootstrap-tagsinput', 'typeahead', 'are-you-sure']);
        Assets::addStylesheets(['bootstrap-tagsinput']);
        return view('theme::options');
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     * @author Sang Nguyen
     */
    public function postUpdate(Request $request)
    {
        $sections = ThemeOption::constructSections();
        foreach ($sections as $section) {
            foreach ($section['fields'] as $field) {
                $key = $field['attributes']['name'];
                ThemeOption::setOption($key, $request->input($key, 0));
            }
        }
        Setting::save();
        return redirect()->back()->with('success_msg', trans('bases::notices.update_success_message'));
    }

    /**
     * @param $theme
     * @return \Illuminate\Http\RedirectResponse
     * @author Sang Nguyen
     */
    public function getActiveTheme($theme)
    {
        Setting::set('theme', $theme);
        Setting::save();
        Artisan::call('cache:clear');
        return redirect()->route('theme.list')->with('success_msg', trans('theme::theme.active_success'));
    }
}