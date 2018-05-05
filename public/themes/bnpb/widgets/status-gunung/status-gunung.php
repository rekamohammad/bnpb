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
            'name' => 'Status Gunung',
			'awas' => 'Status Awas',
			'Gunung1' => 'Nama Gunung',
			'dategunung1' => 'Tanggal',
            'siaga' => 'siaga',
			'Gunung2' => 'Nama Gunung',
			'dategunung2' => 'Tanggal',
			'Gunung3' => 'Nama Gunung',
			'dategunung3' => 'Tanggal',
			'Gunung4' => 'Nama Gunung',
			'dategunung4' => 'Tanggal',
			'Gunung5' => 'Nama Gunung',
			'dategunung5' => 'Tanggal',
			'description' => __('This is a sample widget'),
        ]);
    }
}