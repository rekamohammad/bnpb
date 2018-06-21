<?php

use Botble\Widget\AbstractWidget;

class StatusGunungWidget extends AbstractWidget
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
    protected $widgetDirectory = 'status-gunung';

    /**
     * Widget constructor.
     * @author Sang Nguyen
     */
    public function __construct()
    {
        parent::__construct([
            'name' => 'Status Gunung Api',
            'description' => __('This is a sample widget'),
            'content' => null,
        ]);
    }
}