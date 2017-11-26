<?php

use Botble\Setting\Facades\SettingFacade;

if (!function_exists('setting')) {
    /**
     * Get the setting instance.
     *
     * @param $key
     * @param $default
     * @return array|\Botble\Setting\Setting
     * @author Sang Nguyen
     */
    function setting($key = null, $default = null)
    {
        if (!empty($key)) {
            try {
                return Setting::get($key, $default);
            } catch (Exception $exception) {
                info($exception->getMessage());
                return $default;
            }
        }
        return SettingFacade::getFacadeRoot();
    }
}
