<?php

use Botble\Widget\AbstractWidget;

class StreamingTwitterWidget extends AbstractWidget
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
    protected $widgetDirectory = 'streaming-twitter';

    /**
     * Widget constructor.
     * @author Sang Nguyen
     */
    public function __construct()
    {
        parent::__construct([
            'name' => 'StreamingTwitter',
            'description' => __('This is a sample widget'),
        ]);
    }
}