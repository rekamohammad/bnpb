<?php

register_sidebar([
    'id' => 'footer_sidebar',
    'name' => 'Footer sidebar',
    'description' => 'This is footer sidebar section',
]);

require_once __DIR__ . '/../widgets/custom-menu/custom-menu.php';
require_once __DIR__ . '/../widgets/recent-posts/recent-posts.php';
require_once __DIR__ . '/../widgets/facebook/facebook.php';
require_once __DIR__ . '/../widgets/popular-posts/popular-posts.php';
require_once __DIR__ . '/../widgets/video-posts/video-posts.php';
require_once __DIR__ . '/../widgets/info-bencana/info-bencana.php';
require_once __DIR__ . '/../widgets/siaga-bencana/siaga-bencana.php';
require_once __DIR__ . '/../widgets/status-gunung/status-gunung.php';
require_once __DIR__ . '/../widgets/gempa/gempa.php';
require_once __DIR__ . '/../widgets/streaming-twitter/streaming-twitter.php';
require_once __DIR__ . '/../widgets/banner/banner.php';

register_widget(CustomMenuWidget::class);
register_widget(RecentPostsWidget::class);
register_widget(FacebookWidget::class);
register_widget(PopularPostsWidget::class);
register_widget(VideoPostsWidget::class);
register_widget(InfoBencanaWidget::class);
register_widget(SiagaBencanaWidget::class);
register_widget(StatusGunungWidget::class);
register_widget(GempaWidget::class);
register_widget(StreamingTwitterWidget::class);
register_widget(BannerWidget::class);