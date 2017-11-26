<?php

namespace Botble\Blog\Supports;

class PostFormat
{
    /**
     * @var array
     */
    protected static $formats = [
        '' => [
            'key' => '',
            'icon' => null,
            'name' => 'Default',
        ],
        'gallery' => [
            'key' => 'gallery',
            'icon' => 'fa fa-image-o',
            'name' => 'Gallery',
        ],
        'audio' => [
            'key' => 'audio',
            'icon' => 'fa fa-play',
            'name' => 'Audio',
        ],
        'video' => [
            'key' => 'video',
            'icon' => 'fa fa-camera-o',
            'name' => 'Video',
        ],
        'pdf' => [
            'key' => 'pdf',
            'icon' => 'fa fa-book',
            'name' => 'PDF',
        ],
    ];

    /**
     * @param $formats
     * @return void
     * @author Sang Nguyen
     * @since 16-09-2016
     */
    public static function registerPostFormat(array $formats = [])
    {
        foreach ($formats as $key => $format) {
            self::$formats[$key] = $format;
        }
    }

    /**
     * @param bool $convert_to_list
     * @return array
     * @author Sang Nguyen
     * @since 16-09-2016
     */
    public static function getPostFormats($convert_to_list = false)
    {
        if ($convert_to_list) {
            $results = [];
            foreach (self::$formats as $key => $item) {
                $results[] = [
                    $key,
                    $item['name'],
                ];
            }
            return $results;
        }
        return self::$formats;
    }
}
