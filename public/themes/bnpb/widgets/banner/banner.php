<?php

use Botble\Widget\AbstractWidget;

class BannerWidget extends AbstractWidget
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
    protected $frontendTemplate = 'frontend';

    /**
     * @var string
     */
    protected $backendTemplate = 'backend';

    /**
     * @var string
     */
    protected $widgetDirectory = 'banner';

    /**
     * Widget constructor.
     * @author Sang Nguyen
     */
    public function __construct()
    {
        parent::__construct([
            'name' => 'Banner',
            'description' => __('Only image URL.'),
            'image_url' => 'http://example.com/example-image.jpg',
            'link_url'  => 'http://example.com/',
            'link_target'  => '_top / _blank',
        ]);
    }
}