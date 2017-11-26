<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Inherit from another theme
    |--------------------------------------------------------------------------
    |
    | Set up inherit from another if the file is not exists,
    | this is work with "layouts", "partials" and "views"
    |
    | [Notice] assets cannot inherit.
    |
    */

    'inherit' => null, //default

    /*
    |--------------------------------------------------------------------------
    | Listener from events
    |--------------------------------------------------------------------------
    |
    | You can hook a theme when event fired on activities
    | this is cool feature to set up a title, meta, default styles and scripts.
    |
    | [Notice] these event can be override by package config.
    |
    */

    'events' => [

        // Before event inherit from package config and the theme that call before,
        // you can use this event to set meta, breadcrumb template or anything
        // you want inheriting.
        'before' => function($theme)
        {
            // You can remove this line anytime.
            $theme->setTitle('Copyright ©  2017 - BNPB');
        },

        // Listen on event before render a theme,
        // this event should call to assign some assets,
        // breadcrumb template.
        'beforeRenderTheme' => function($theme)
        {
            // You may use this event to set up your assets.
            $theme->asset()->container('footer')->usePath()->add('modernizr-js', 'js/modernizr.js');
            $theme->asset()->container('footer')->usePath()->add('core-js', 'js/core.min.js');
            $theme->asset()->container('footer')->usePath()->add('app-js', 'js/app.min.js');

            $theme->asset()->usePath()->add('style-css', 'css/style.css');
            $theme->asset()->usePath()->add('red-theme-css', 'css/' . theme_option('theme_color', 'red') . '.css', [], ['id' => 'style_color']);
            $theme->asset()->usePath()->add('custom-css', 'css/custom.css');

            if (app()->environment() != 'production') {
                $theme->asset()->container('footer')->usePath()->add('demo-js', 'js/demo.js');
                $theme->asset()->usePath()->add('demo-css', 'css/demo.min.css');
            }

            $theme->composer(['page', 'post'], function($view) {
                $view->withShortcodes();
            });
        },

        // Listen on event before render a layout,
        // this should call to assign style, script for a layout.
        'beforeRenderLayout' => [

            'default' => function($theme)
            {
                // $theme->asset()->usePath()->add('ipad', 'css/layouts/ipad.css');
            }
        ]
    ]
];
