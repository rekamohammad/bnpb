<?php

use Botble\Widget\AbstractWidget;

class FacebookWidget extends AbstractWidget
{
    /**
     * The configuration array.
     *
     * @var array
     */
    protected $config = [];

    protected $frontendTemplate = 'frontend';

    protected $backendTemplate = 'backend';

    protected $widgetDirectory = 'facebook';

    public function __construct()
    {
        parent::__construct([
            'name' => 'Facebook',
            'description' => 'Facebook fan page widget',
            'facebook_name' => null,
            'facebook_id' => null,
        ]);
    }
}