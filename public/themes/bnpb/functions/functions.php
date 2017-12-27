<?php

register_page_template([
    'default' => __('Default'),
    'diorama' => __('Diorama'),
    'no-sidebar' => __('No Sidebar')
]);

add_shortcode('google-map', 'Google map', 'Custom map', 'add_google_map_shortcode');
function add_google_map_shortcode ($shortcode) {
    return Theme::partial('google-map', ['address' => $shortcode->content]);
}
shortcode()->setAdminConfig('google-map', Theme::partial('google-map-admin-config'));

add_shortcode('youtube-video', 'Youtube video', 'Add youtube video', 'add_youtube_video_shortcode');
function add_youtube_video_shortcode ($shortcode) {
    return Theme::partial('video', ['url' => $shortcode->content]);
}
shortcode()->setAdminConfig('youtube-video', Theme::partial('youtube-admin-config'));


add_shortcode('pdf-file', 'PDF File', 'PDF File', 'add_pdf_file_shortcode');
function add_pdf_file_shortcode ($shortcode) {
    return Theme::partial('pdf-file', ['url' => $shortcode->content]);
}
shortcode()->setAdminConfig('pdf-file', Theme::partial('pdf-file-admin-config'));

add_shortcode('audio', 'Audio File', 'Audio File', 'add_audio_shortcode');
function add_audio_shortcode ($shortcode) {
    return Theme::partial('audio', ['url' => $shortcode->content]);
}
shortcode()->setAdminConfig('audio', Theme::partial('audio-admin-config'));

add_shortcode('status-alert', 'Status Alert', 'Status Alert', 'add_status_shortcode');
function add_status_shortcode ($shortcode) {
    return Theme::partial('status', ['status' => $shortcode->content]);
}
shortcode()->setAdminConfig('status-alert', Theme::partial('status-admin-config'));


theme_option()->setSection([
    'title' => __('General'),
    'desc' => __('General settings'),
    'id' => 'opt-text-subsection-general',
    'subsection' => true,
    'icon' => 'fa fa-home',
]);

theme_option()->setSection([
    'title' => __('Logo'),
    'desc' => __('Change logo'),
    'id' => 'opt-text-subsection-logo',
    'subsection' => true,
    'icon' => 'fa fa-image',
    'fields' => [
        [
            'id' => 'logo',
            'type' => 'mediaImage',
            'label' => __('Logo'),
            'attributes' => [
                'name' => 'logo',
                'value' => null,
            ],
        ],
        [
            'id' => 'fav',
            'type' => 'mediaImage',
            'label' => __('Favicon'),
            'attributes' => [
                'name' => 'favicon',
                'value' => null,
            ],
        ],
    ],
]);

theme_option()->setField([
    'id' => 'copyright',
    'section_id' => 'opt-text-subsection-general',
    'type' => 'text',
    'label' => __('Copyright'),
    'attributes' => [
        'name' => 'copyright',
        'value' => '© 2017 BNPB. All right reserved.',
        'options' => [
            'class' => 'form-control',
            'placeholder' => __('Change copyright'),
            'data-counter' => 120,
        ]
    ],
    'helper' => __('Copyright on footer of site'),
]);

theme_option()->setField([
    'id' => 'theme-color',
    'section_id' => 'opt-text-subsection-general',
    'type' => 'select',
    'label' => __('Theme color'),
    'attributes' => [
        'name' => 'theme_color',
        'list' => ['red' => 'Red', 'green' => 'Green', 'blue' => 'Blue'],
        'value' => 'red',
        'options' => [
            'class' => 'form-control',
        ],
    ],
    'helper' => __('Primary theme color'),
]);

theme_option()->setSection([
    'title' => __('News Feed'),
    'desc' => __('News Feed'),
    'id' => 'opt-text-subsection-feed',
    'subsection' => true,
    'icon' => 'fa fa-file-text-o',
    'fields' => [
        [
            'id' => 'diorama-middle',
            'type' => 'text',
            'label' => __('Diorama Middle IDs'),
            'attributes' => [
                'name' => 'diorama-middle',
                'value' => '1,2,3',
                'options' => [
                    'class' => 'form-control',
                    'placeholder' => __('Change Diorama Middle IDs'),
                    'data-counter' => 120,
                ]
            ],
        ],
        [
            'id' => 'mountain-status',
            'type' => 'text',
            'label' => __('Mountain Status Category ID'),
            'attributes' => [
                'name' => 'mountain-status',
                'value' => null,
                'options' => [
                    'class' => 'form-control',
                    'placeholder' => __('Change Mountain Status Category ID'),
                    'data-counter' => 20,
                ]
            ],
        ],
        [
            'id' => 'home-tabbed-feed',
            'type' => 'text',
            'label' => __('Home Tabbed Feed ID'),
            'attributes' => [
                'name' => 'home-tabbed-feed',
                'value' => null,
                'options' => [
                    'class' => 'form-control',
                    'placeholder' => __('Change Home Tabbed Feed ID'),
                    'data-counter' => 100,
                ]
            ],
        ],
        [
            'id' => 'home-slider-feed',
            'type' => 'text',
            'label' => __('Home Slider Feed ID'),
            'attributes' => [
                'name' => 'home-slider-feed',
                'value' => null,
                'options' => [
                    'class' => 'form-control',
                    'placeholder' => __('Change Home Slider Feed ID'),
                    'data-counter' => 20,
                ]
            ],
        ],
        [
            'id' => 'home-right-feed',
            'type' => 'text',
            'label' => __('Home Right Feed ID'),
            'attributes' => [
                'name' => 'home-right-feed',
                'value' => null,
                'options' => [
                    'class' => 'form-control',
                    'placeholder' => __('Change Home Right Feed ID'),
                    'data-counter' => 20,
                ]
            ],
        ],
    ],
]);

theme_option()->setSection([
    'title' => __('Home Widget Links'),
    'desc' => __('Update Home Widget Links'),
    'id' => 'opt-text-subsection-links',
    'subsection' => true,
    'icon' => 'fa fa-share-alt',
    'fields' => [
        [
            'id' => 'home-link-diorama',
            'type' => 'text',
            'label' => __('Home Link Diorama'),
            'column' => 'col-md-10',
            'attributes' => [
                'name' => 'home-link-diorama',
                'value' => '#',
                'options' => [
                    'class' => 'form-control',
                    'placeholder' => __('Change Home Link Diorama'),
                    'data-counter' => 120,
                ]
            ],
        ],
        [
            'id' => 'home-link-diorama-target',
            'type' => 'select',
            'label' => 'Target Link',
            'column' => 'col-md-2',
            'attributes' => [
                'name' => 'home-link-diorama-target',
                'list' => ['_self' => 'Self', '_blank' => 'Blank'],
                'value' => '_self',
                'options' => [
                    'class' => 'form-control',
                ],
            ],
        ],
        [
            'id' => 'home-link-perpustakaan-online',
            'type' => 'text',
            'label' => __('Home Link Perpustakaan Online'),
            'column' => 'col-md-10',
            'attributes' => [
                'name' => 'home-link-perpustakaan-online',
                'value' => '#',
                'options' => [
                    'class' => 'form-control',
                    'placeholder' => __('Change Home Link Perpustakaan Online'),
                    'data-counter' => 120,
                ]
            ],
        ],
        [
            'id' => 'home-link-perpustakaan-online-target',
            'type' => 'select',
            'label' => 'Target Link',
            'column' => 'col-md-2',
            'attributes' => [
                'name' => 'home-link-perpustakaan-online-target',
                'list' => ['_self' => 'Self', '_blank' => 'Blank'],
                'value' => '_self',
                'options' => [
                    'class' => 'form-control',
                ],
            ],
        ],
        [
            'id' => 'home-link-bnpb-tv',
            'type' => 'text',
            'label' => __('Home Link BNPB TV'),
            'column' => 'col-md-10',
            'attributes' => [
                'name' => 'home-link-bnpb-tv',
                'value' => '#',
                'options' => [
                    'class' => 'form-control',
                    'placeholder' => __('Change Home Link BNPB TV'),
                    'data-counter' => 120,
                ]
            ],
        ],
        [
            'id' => 'home-link-bnpb-tv-target',
            'type' => 'select',
            'label' => 'Target Link',
            'column' => 'col-md-2',
            'attributes' => [
                'name' => 'home-link-bnpb-tv-target',
                'list' => ['_self' => 'Self', '_blank' => 'Blank'],
                'value' => '_self',
                'options' => [
                    'class' => 'form-control',
                ],
            ],
        ],
        [
            'id' => 'home-link-data-informasi-bencana',
            'type' => 'text',
            'label' => __('Home Link Data Informasi Bencana'),
            'column' => 'col-md-10',
            'attributes' => [
                'name' => 'home-link-data-informasi-bencana',
                'value' => '#',
                'options' => [
                    'class' => 'form-control',
                    'placeholder' => __('Change Home Link Data Informasi Bencana'),
                    'data-counter' => 120,
                ]
            ],
        ],
        [
            'id' => 'home-link-data-informasi-bencana-target',
            'type' => 'select',
            'label' => 'Target Link',
            'column' => 'col-md-2',
            'attributes' => [
                'name' => 'home-link-data-informasi-bencana-target',
                'list' => ['_self' => 'Self', '_blank' => 'Blank'],
                'value' => '_self',
                'options' => [
                    'class' => 'form-control',
                ],
            ],
        ],
        [
            'id' => 'home-link-geospasial-bencana',
            'type' => 'text',
            'label' => __('Home Link Geospasial Bencana'),
            'column' => 'col-md-10',
            'attributes' => [
                'name' => 'home-link-geospasial-bencana',
                'value' => '#',
                'options' => [
                    'class' => 'form-control',
                    'placeholder' => __('Change Home Link Geospasial Bencana'),
                    'data-counter' => 120,
                ]
            ],
        ],
        [
            'id' => 'home-link-geospasial-bencana-target',
            'type' => 'select',
            'label' => 'Target Link',
            'column' => 'col-md-2',
            'attributes' => [
                'name' => 'home-link-geospasial-bencana-target',
                'list' => ['_self' => 'Self', '_blank' => 'Blank'],
                'value' => '_self',
                'options' => [
                    'class' => 'form-control',
                ],
            ],
        ],
        [
            'id' => 'home-link-pantauan-bencana',
            'type' => 'text',
            'label' => __('Home Link Pantauan Bencana'),
            'column' => 'col-md-10',
            'attributes' => [
                'name' => 'home-link-pantauan-bencana',
                'value' => '#',
                'options' => [
                    'class' => 'form-control',
                    'placeholder' => __('Change Home Link Pantauan Bencana'),
                    'data-counter' => 120,
                ]
            ],
        ],
        [
            'id' => 'home-link-pantauan-bencana-target',
            'type' => 'select',
            'label' => 'Target Link',
            'column' => 'col-md-2',
            'attributes' => [
                'name' => 'home-link-pantauan-bencana-target',
                'list' => ['_self' => 'Self', '_blank' => 'Blank'],
                'value' => '_self',
                'options' => [
                    'class' => 'form-control',
                ],
            ],
        ],
        [
            'id' => 'home-link-lpse',
            'type' => 'text',
            'label' => __('Home Link LPSE'),
            'column' => 'col-md-10',
            'attributes' => [
                'name' => 'home-link-lpse',
                'value' => '#',
                'options' => [
                    'class' => 'form-control',
                    'placeholder' => __('Change Home Link LPSE'),
                    'data-counter' => 120,
                ]
            ],
        ],
        [
            'id' => 'home-link-lpse-target',
            'type' => 'select',
            'label' => 'Target Link',
            'column' => 'col-md-2',
            'attributes' => [
                'name' => 'home-link-lpse-target',
                'list' => ['_self' => 'Self', '_blank' => 'Blank'],
                'value' => '_self',
                'options' => [
                    'class' => 'form-control',
                ],
            ],
        ],
        [
            'id' => 'home-link-ppid',
            'type' => 'text',
            'label' => __('Home Link PPID'),
            'column' => 'col-md-10',
            'attributes' => [
                'name' => 'home-link-ppid',
                'value' => '#',
                'options' => [
                    'class' => 'form-control',
                    'placeholder' => __('Change Home Link PPID'),
                    'data-counter' => 120,
                ]
            ],
        ],
        [
            'id' => 'home-link-ppid-target',
            'type' => 'select',
            'label' => 'Target Link',
            'column' => 'col-md-2',
            'attributes' => [
                'name' => 'home-link-ppid-target',
                'list' => ['_self' => 'Self', '_blank' => 'Blank'],
                'value' => '_self',
                'options' => [
                    'class' => 'form-control',
                ],
            ],
        ],
        [
            'id' => 'home-link-inarisk',
            'type' => 'text',
            'label' => __('Home Link Inarisk'),
            'column' => 'col-md-10',
            'attributes' => [
                'name' => 'home-link-inarisk',
                'value' => '#',
                'options' => [
                    'class' => 'form-control',
                    'placeholder' => __('Change Home Link Inarisk'),
                    'data-counter' => 120,
                ]
            ],
        ],
        [
            'id' => 'home-link-inarisk-target',
            'type' => 'select',
            'label' => 'Target Link',
            'column' => 'col-md-2',
            'attributes' => [
                'name' => 'home-link-inarisk-target',
                'list' => ['_self' => 'Self', '_blank' => 'Blank'],
                'value' => '_self',
                'options' => [
                    'class' => 'form-control',
                ],
            ],
        ],

    ],
]);

theme_option()->setSection([
    'title' => __('Comments'),
    'desc' => __('Commment'),
    'id' => 'opt-text-subsection-comments',
    'subsection' => true,
    'icon' => 'fa fa-comments',
    'fields' => [
        [
            'id' => 'facebook-app-id',
            'type' => 'text',
            'label' => __('Facebook Comment App ID'),
            'attributes' => [
                'name' => 'facebook-app-id',
                'value' => null,
                'options' => [
                    'class' => 'form-control',
                    'placeholder' => __('Eg: 265134686905'),
                    'data-counter' => 20,
                ]
            ],
        ],
    ],
]);

// theme_option()->setField([
//     'id' => 'top-banner',
//     'section_id' => 'opt-text-subsection-general',
//     'type' => 'text',
//     'label' => __('Top banner'),
//     'attributes' => [
//         'name' => 'top_banner',
//         'value' => '/themes/newstv/assets/images/banner.png',
//         'options' => [
//             'class' => 'form-control',
//             'placeholder' => __('Input image URL...'),
//         ]
//     ],
// ]);

theme_option()->setArgs(['debug' => false]);