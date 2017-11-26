<?php

namespace Botble\Assets;

use Botble\Base\Supports\Language;
use File;

/**
 * Class Assets
 * @package Botble\Assets
 * @author Sang Nguyen
 * @since 22/07/2015 11:23 PM
 */
class Assets
{
    /**
     * @var array
     */
    protected $javascript = [];

    /**
     * @var array
     */
    protected $stylesheets = [];

    /**
     * @var array
     */
    protected $appModules = [];

    /**
     * @var mixed
     */
    protected $build;

    /**
     * @var array
     */
    protected $appendedJs = [
        'top' => [],
        'bottom' => [],
    ];

    /**
     * @var array
     */
    protected $appendedCss = [];

    /**
     * Assets constructor.
     * @author Sang Nguyen
     */
    public function __construct()
    {
        $this->javascript = config('assets.javascript');
        $this->stylesheets = config('assets.stylesheets');

        $this->build = time();
        if (app()->environment() == 'production') {
            $this->build = config('cms.version');
        }
    }

    /**
     * Add Javascript to current module
     *
     * @param array $assets
     * @return $this
     * @author Sang Nguyen
     */
    public function addJavascript($assets)
    {
        if (!is_array($assets)) {
            $assets = [$assets];
        }
        $this->javascript = array_merge($this->javascript, $assets);
        return $this;
    }

    /**
     * Add Css to current module
     *
     * @param array $assets
     * @return $this
     * @author Sang Nguyen
     */
    public function addStylesheets($assets)
    {
        if (!is_array($assets)) {
            $assets = [$assets];
        }
        $this->stylesheets = array_merge($this->stylesheets, $assets);
        return $this;
    }

    /**
     * @param $assets
     * @return $this
     * @author Tedozi Manson
     */
    public function addStylesheetsDirectly($assets)
    {
        if (!is_array($assets)) {
            $assets = func_get_args();
        }
        $this->appendedCss = array_merge($this->appendedCss, $assets);
        return $this;
    }
    /**
     * @param $assets
     * @param string $location
     * @return $this
     *  @author Tedozi Manson
     */
    public function addJavascriptDirectly($assets, $location = 'bottom')
    {
        $js = array_merge((array)$assets, $this->appendedJs[$location]);
        $this->appendedJs[$location] = $js;
        return $this;
    }

    /**
     * Add Module to current module
     *
     * @param array $modules
     * @return $this;
     * @author Sang Nguyen
     */
    public function addAppModule($modules)
    {
        if (!is_array($modules)) {
            $modules = [$modules];
        }
        $this->appModules = array_merge($this->appModules, $modules);
        return $this;
    }

    /**
     * Remove Css to current module
     *
     * @param array $assets
     * @return $this;
     * @author Sang Nguyen
     */
    public function removeStylesheets($assets)
    {
        if (!is_array($assets)) {
            $assets = [$assets];
        }
        foreach ($assets as $rem) {
            unset($this->stylesheets[array_search($rem, $this->stylesheets)]);
        }
        return $this;
    }

    /**
     * Add Javascript to current module
     *
     * @param array $assets
     * @return $this;
     * @author Sang Nguyen
     */
    public function removeJavascript($assets)
    {
        if (!is_array($assets)) {
            $assets = [$assets];
        }
        foreach ($assets as $rem) {
            unset($this->javascript[array_search($rem, $this->javascript)]);
        }
        return $this;
    }

    /**
     * Get All Javascript in current module
     *
     * @param string $location : top or bottom
     * @param boolean $version : show version?
     * @return array
     * @author Sang Nguyen
     */
    public function getJavascript($location = null, $version = true)
    {
        $scripts = [];
        if (!empty($this->javascript)) {
            // get the final scripts need for page
            $this->javascript = array_unique($this->javascript);
            foreach ($this->javascript as $js) {
                $jsConfig = 'assets.resources.javascript.' . $js;

                if (config()->has($jsConfig)) {
                    if ($location != null && config($jsConfig . '.location') !== $location) {
                        // Skip assets that don't match this location
                        continue;
                    }

                    $src = config($jsConfig . '.src.local');
                    $cdn = false;
                    if (config($jsConfig . '.use_cdn') && !config('assets.offline')) {
                        $src = config($jsConfig . '.src.cdn');
                        $cdn = true;
                    }

                    if (config($jsConfig . '.include_style')) {
                        $this->addStylesheets([$js]);
                    }

                    $version = $version ? '?ver=' . $this->build : '';
                    if (!is_array($src)) {
                        $scripts[] = $src . $version;
                    } else {
                        foreach ($src as $s) {
                            $scripts[] = $s . $version;
                        }
                    }

                    if (empty($src) && $cdn && $location === 'top' && config()->has($jsConfig . '.fallback')) {
                        // Fallback to local script if CDN fails
                        $fallback = config($jsConfig . '.fallback');
                        $scripts[] = [
                            'url' => $src,
                            'fallback' => $fallback,
                            'fallbackURL' => config($jsConfig . '.src.local'),
                        ];
                    }
                }
            }
        }

        if (isset($this->appendedJs[$location])) {
            $scripts = array_merge($scripts, $this->appendedJs[$location]);
        }

        return $scripts;
    }

    /**
     * Get All CSS in current module
     *
     * @param array $lastModules : append last CSS to current module
     * @param boolean $version : show version?
     * @return array
     * @author Sang Nguyen
     */
    public function getStylesheets($lastModules = [], $version = true)
    {
        $stylesheets = [];
        if (!empty($this->stylesheets)) {
            if (!empty($lastModules)) {
                $this->stylesheets = array_merge($this->stylesheets, $lastModules);
            }
            // get the final scripts need for page
            $this->stylesheets = array_unique($this->stylesheets);
            foreach ($this->stylesheets as $style) {
                if (config()->has('assets.resources.stylesheets.' . $style)) {

                    $src = config('assets.resources.stylesheets.' . $style . '.src.local');
                    if (config('assets.resources.stylesheets.' . $style . '.use_cdn') && !config('assets.offline')) {
                        $src = config('assets.resources.stylesheets.' . $style . '.src.cdn');
                    }

                    if (!is_array($src)) {
                        $src = [$src];
                    }
                    foreach ($src as $s) {
                        $stylesheets[] = $s . ($version ? '?ver=' . $this->build : '');
                    }
                }
            }
        }

        $stylesheets = array_merge($stylesheets, $this->appendedCss);


        return $stylesheets;
    }

    /**
     * Get all modules in current module
     * @param boolean $version : show version?
     * @return array
     * @author Sang Nguyen
     */
    public function getAppModules($version = true)
    {
        $modules = [];
        if (!empty($this->appModules)) {
            // get the final scripts need for page
            $this->appModules = array_unique($this->appModules);
            foreach ($this->appModules as $module) {
                if (($module = $this->getModule($module, $version)) !== null) {
                    $modules[] = $module;
                }
            }
        }

        return $modules;
    }

    /**
     * Get a modules
     * @param string $module : module's name
     * @param boolean $version : show version?
     * @return string
     */
    protected function getModule($module, $version)
    {
        $pathPrefix = public_path() . '/vendor/core/js/app_modules/' . $module;

        $file = null;

        if (file_exists($pathPrefix . '.min.js')) {
            $file = $module . '.min.js';
        } elseif (file_exists($pathPrefix . '.js')) {
            $file = $module . '.js';
        }

        if ($file) {
            return '/vendor/core/js/app_modules/' . $file . ($version ? '?ver=' . $this->build : '');
        }
        return null;
    }

    /**
     * Get all admin themes
     * @return array
     * @author Sang Nguyen
     */
    public function getThemes()
    {
        $themes = [];
        $public_path = public_path();
        foreach (File::files($public_path . '/vendor/core/css/themes') as $file) {
            $name = '/vendor/core/css/themes/' . basename($file);
            if (!str_contains($file, '.css.map')) {
                $themes[basename($file, '.css')] = $name;
            }
        }

        return $themes;
    }

    /**
     * @return array
     * @author Sang Nguyen
     */
    public function getAdminLocales()
    {
        $languages = [];
        $locales = scan_folder(resource_path('lang'));
        if (in_array('vendor', $locales)) {
            $locales = array_merge($locales, scan_folder(resource_path('lang/vendor')));
        }

        foreach ($locales as $locale) {
            if ($locale == 'vendor') {
                continue;
            }
            foreach (Language::getListLanguages() as $language) {
                if ($locale == $language[0]) {
                    $languages[$locale] = [
                        'name' => $language[2],
                        'flag' => $language[4]
                    ];
                }
            }
        }
        return $languages;
    }
}
