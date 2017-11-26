<?php

use Botble\Widget\AbstractWidget;

class PopularPostsWidget extends AbstractWidget
{
    /**
     * The configuration array.
     *
     * @var array
     */
    protected $config = [];

    protected $frontendTemplate = 'frontend';

    protected $backendTemplate = 'backend';

    protected $widgetDirectory = 'popular-posts';

    public function __construct()
    {
        parent::__construct([
            'name' => 'PopularPosts',
            'description' => 'This is a sample widget',
            'number_display' => 5,
        ]);
    }
}