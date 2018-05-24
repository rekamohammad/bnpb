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
			'url_video' => 'https://bnpb.go.id//uploads/24/video/BNPB-PSA-Kenali-Bahayanya-Kurangi-Risikonya.mp4',
            'url_more_video' => 'http://bnpbindonesia.tv/',
			'description' => 'description name',
            'number_display' => 5,
        ]);
    }
}