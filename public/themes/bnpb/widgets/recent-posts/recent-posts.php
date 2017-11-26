<?php

use Botble\Widget\AbstractWidget;
use Botble\Widget\Repositories\Interfaces\WidgetInterface;

class RecentPostsWidget extends AbstractWidget
{
    /**
     * The configuration array.
     *
     * @var array
     */
    protected $config = [];

    protected $frontendTemplate = 'frontend';

    protected $backendTemplate = 'backend';

    protected $widgetDirectory = 'recent-posts';

    public function __construct()
    {
        parent::__construct([
            'name' => __('Recent posts'),
            'description' => __('Recent posts widget.'),
            'number_display' => 5,
        ]);
    }
}