<?php

namespace Botble\Analytics\Exceptions;

use Exception;

class InvalidConfiguration extends Exception
{
    /**
     * @return static
     * @author Freek Van der Herten <freek@spatie.be>
     * @modified Sang Nguyen
     */
    public static function viewIdNotSpecified()
    {
        return new static(trans('analytics::analytics.view_id_not_specified', ['version' => config('cms.version')]));
    }

    /**
     * @param $path
     * @return static
     * @author Freek Van der Herten <freek@spatie.be>
     * @modified Sang Nguyen
     */
    public static function credentialsJsonDoesNotExist($path)
    {
        return new static(trans('analytics::analytics.credential_json_not_found_at', ['path' => $path, 'version' => config('cms.version')]));
    }
}
