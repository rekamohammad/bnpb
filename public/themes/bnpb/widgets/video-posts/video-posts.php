<?php

use Botble\Widget\AbstractWidget;

class VideoPostsWidget extends AbstractWidget
{
    /**
     * The configuration array.
     *
     * @var array
     */
    protected $config = [];

    protected $frontendTemplate = 'frontend';

    protected $backendTemplate = 'backend';

    protected $widgetDirectory = 'video-posts';

    public function __construct()
    {
        parent::__construct([
            'name' => 'VideoPosts',
            'description' => 'This is a sample widget',
            'number_display' => 5,
        ]);
    }
}