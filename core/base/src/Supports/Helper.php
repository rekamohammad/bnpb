<?php

namespace Botble\Base\Supports;

use Eloquent;
use Exception;
use File;
use Request;

class Helper
{
    /**
     * Load module's helpers
     * @param $directory
     * @author Sang Nguyen
     * @since 2.0
     */
    public static function autoload($directory)
    {
        $helpers = File::glob($directory . '/*.php');
        foreach ($helpers as $helper) {
            File::requireOnce($helper);
        }
    }

    /**
     * @param Eloquent $object
     * @param string $session_name
     * @return bool
     * @author Sang Nguyen
     */
    public static function handleViewCount(Eloquent $object, $session_name)
    {
        $blank_array = [];
        if (!array_key_exists($object->id, session()->get($session_name, $blank_array))) {
            try {
                $object->views += 1;
                $object->save();
                session()->put($session_name . '.' . $object->id, time());
                return true;
            } catch (Exception $ex) {
                return false;
            }
        }

        return false;
    }

    /**
     * Format Log data
     *
     * @param array $input
     * @param string $line
     * @param string $function
     * @param string $class
     * @return array
     * @author Sang Nguyen
     */
    public static function formatLog($input, $line = '', $function = '', $class = '')
    {
        return array_merge($input, [
            'user_id' => acl_check_login() ? acl_get_current_user_id() : 'Testing',
            'ip' => Request::ip(),
            'line'  => $line,
            'function'  => $function,
            'class' => $class,
            'userAgent' => Request::header('User-Agent')
        ]);
    }
}