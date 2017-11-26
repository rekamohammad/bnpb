<?php

use Botble\Base\Models\Plugin;

if (!function_exists('get_all_plugins')) {
    /**
     * @param null $status
     * @return mixed
     * @author Sang Nguyen
     */
    function get_all_plugins($status = null)
    {
        $condition = [];
        if ($status != null) {
            $condition['status'] = $status;
        }
        return Plugin::where($condition)->get();
    }
}