<?php

if (!function_exists('rv_media_get_current_user')) {
    /**
     * @return mixed
     * @author Sang Nguyen
     */
    function rv_media_get_current_user() {
        return acl_get_current_user();
    }
}

if (!function_exists('rv_media_get_current_user_id')) {
    /**
     * @return int
     * @author Sang Nguyen
     */
    function rv_media_get_current_user_id() {
        return acl_get_current_user_id();
    }
}