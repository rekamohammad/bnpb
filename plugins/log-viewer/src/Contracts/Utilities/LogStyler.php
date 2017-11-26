<?php

namespace Botble\LogViewer\Contracts\Utilities;

interface LogStyler
{

    /**
     * Make level icon.
     *
     * @param  string $level
     * @param  string|null $default
     *
     * @return string
     * @author ARCANEDEV <arcanedev.maroc@gmail.com>
     */
    public function icon($level, $default = null);

    /**
     * Get level color.
     *
     * @param  string $level
     * @param  string|null $default
     *
     * @return string
     * @author ARCANEDEV <arcanedev.maroc@gmail.com>
     */
    public function color($level, $default = null);
}
