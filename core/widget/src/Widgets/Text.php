<?php

namespace Botble\Widget\Widgets;

use Botble\Widget\AbstractWidget;

class Text extends AbstractWidget
{
    /**
     * The configuration array.
     *
     * @var array
     */
    protected $config = [];

    /**
     * @var string
     */
    protected $frontendTemplate = 'widgets::widgets.text.frontend';

    /**
     * @var string
     */
    protected $backendTemplate = 'widgets::widgets.text.backend';

    /**
     * @var string
     */
    protected $widgetDirectory;

    /**
     * @var bool
     */
    protected $is_core = true;

    /**
     * Text constructor.
     * @author Sang Nguyen
     */
    public function __construct()
    {
        parent::__construct([
            'name' => trans('widgets::global.widget_text'),
            'description' => trans('widgets::global.widget_text_description'),
            'content' => null,
        ]);
    }
}