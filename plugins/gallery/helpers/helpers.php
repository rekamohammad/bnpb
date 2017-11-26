<?php

use Botble\Gallery\Repositories\Interfaces\GalleryInterface;
use Botble\Gallery\Repositories\Interfaces\GalleryMetaInterface;

if (!function_exists('gallery_meta_data')) {
    /**
     * @param $id
     * @param $type
     * @param array $select
     * @return mixed
     * @author Sang Nguyen
     */
    function gallery_meta_data($id, $type, array $select = ['images'])
    {
        $meta = app(GalleryMetaInterface::class)->getFirstBy(['content_id' => $id, 'reference' => $type], $select);
        if (!empty($meta)) {
            return $meta->images;
        }
        return [];
    }
}

if (!function_exists('get_galleries')) {
    /**
     * @param $limit
     * @return mixed
     * @author Sang Nguyen
     */
    function get_galleries($limit)
    {
        return app(GalleryInterface::class)->getFeaturedGalleries($limit);
    }
}

if (!function_exists('render_galleries')) {
    /**
     * @param $limit
     * @return mixed
     * @author Sang Nguyen
     */
    function render_galleries($limit)
    {
        return view('gallery::gallery', compact('limit'));
    }
}

if (!function_exists('get_list_galleries')) {
    /**
     * @param array $condition
     * @return mixed
     * @author Sang Nguyen
     */
    function get_list_galleries(array $condition)
    {
        return app(GalleryInterface::class)->allBy($condition);
    }
}
