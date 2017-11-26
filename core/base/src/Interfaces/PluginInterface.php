<?php

namespace Botble\Base\Interfaces;

interface PluginInterface
{

    /**
     * @return array
     * @author Sang Nguyen
     */
    public static function permissions();

    /**
     * @author Sang Nguyen
     */
    public static function activate();

    /**
     * @author Sang Nguyen
     */
    public static function deactivate();

    /**
     * @author Sang Nguyen
     */
    public static function remove();
}