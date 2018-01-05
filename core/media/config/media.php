<?php

return [
    'mode' => env('RV_MEDIA_MODE', 'advanced'), // Use "simple" or "advanced"
    'sizes' => [
        'thumb' => '150x150',
        'thumbPotrait' => '262x285',
        'thumbLandscape' => '262x185',
        'featured' => '560x380',
        'medium' => '540x360',
    ],
    'upload' => [
        'folder' => 'uploads',
        'path' => public_path('uploads'),
        // User will can not create folder with these name
        'reserved_names' => [
            // 'avatars',
        ],
    ],
    'route' => [
        'prefix' => env('ADMIN_DIR', 'admin') . '/media',
        'middleware' => ['web', 'auth'],
        'options' => [
            'permission' => 'media.index',
        ],
    ],
    'permissions' => [
        'folders.create',
        'folders.edit',
        'folders.trash',
        'folders.delete',
        'files.create',
        'files.edit',
        'files.trash',
        'files.delete',
    ],
    'cache' => [
        'enable' => env('RV_MEDIA_ENABLE_CACHE', false), // true or false
        'cache_time' => env('RV_MEDIA_CACHE_TIME', 10),
        'stored_keys' => storage_path('media_cache_keys.json'), // Cache config
    ],
    'allow_external_services' => env('RV_MEDIA_ALLOW_EXTERNAL_SERVICES', false),
    'external_services' => [
        'youtube',
        'vimeo',
        'dailymotion',
        'instagram',
        'vine',
    ],
    'libraries' => [
        'stylesheets' => [
            //'https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css',
            //'vendor/media/packages/font-awesome/css/font-awesome.min.css',
            'vendor/media/packages/fancybox/dist/jquery.fancybox.css',
            //'vendor/media/packages/toastr/toastr.min.css',
            'vendor/media/packages/jquery-context-menu/jquery.contextMenu.min.css',
            'vendor/media/packages/focuspoint/css/focuspoint.css',
            'vendor/media/css/media.css?v=' . time(),
        ],
        'javascript' => [
            'vendor/media/packages/underscore/underscore-min.js',
            //'https://cdnjs.cloudflare.com/ajax/libs/jquery/1.12.4/jquery.min.js',
            //'https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js',
            'vendor/media/packages/clipboard/clipboard.min.js',
            'vendor/media/packages/fancybox/dist/jquery.fancybox.js',
            'vendor/media/packages/dropzone/dropzone.js',
            //'vendor/media/packages/toastr/toastr.min.js',
            //'vendor/media/packages/pace/pace.min.js',
            'vendor/media/packages/jquery-context-menu/jquery.ui.position.min.js',
            'vendor/media/packages/jquery-context-menu/jquery.contextMenu.min.js',
            'vendor/media/packages/focuspoint/js/jquery.focuspoint.min.js',
            'vendor/media/js/media.js?v=' . time(),
            'vendor/media/js/focus.js?v=' . time(),
        ],
    ],
    'allowed_mime_types' => env('RV_MEDIA_ALLOWED_MIME_TYPES', 'jpg,jpeg,png,gif,txt,docx,zip,mp3,bmp,csv,docs,xls,xlsx,ppt,pptx,pdf,mp4'),
    'mime_types' => [
        'image' => [
            'image/png',
            'image/jpeg',
            'image/gif',
            'image/bmp',
        ],
        'video' => [
            'video/mp4',
        ],
        'pdf' => [
            'application/pdf',
        ],
        'excel' => [
            'application/excel',
            'application/x-excel',
            'application/x-msexcel',
        ],
        'youtube' => [
            'youtube',
        ],
    ],
    'max_file_size_upload' => env('RV_MEDIA_MAX_FILE_SIZE_UPLOAD', 4 * 1024), // Maximum size to upload
    'default-img' => env('RV_MEDIA_DEFAULT_IMAGE', '/vendor/core/images/default-image.png'), // Default image
    'sidebar_display' => env('RV_MEDIA_SIDEBAR_DISPLAY', 'horizontal'), // Use "vertical" or "horizontal"
    'user_attributes' => 'users.id, CONCAT(users.first_name, " ", users.last_name) AS name',
    'layouts' => [
        'master' => 'bases::layouts.master',
        'main' => 'content',
        'header' => 'head',
        'footer' => 'javascript',
    ],
];
