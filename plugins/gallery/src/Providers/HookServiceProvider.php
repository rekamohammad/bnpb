<?php

namespace Botble\Gallery\Providers;

use Botble\Gallery\Repositories\Interfaces\GalleryInterface;
use Botble\Gallery\Repositories\Interfaces\GalleryMetaInterface;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\ServiceProvider;

class HookServiceProvider extends ServiceProvider
{

    /**
     * @author Sang Nguyen
     */
    public function boot()
    {
        add_action(BASE_ACTION_META_BOXES, [$this, 'addGalleryBox'], 13, 3);
        add_action(BASE_ACTION_AFTER_CREATE_CONTENT, [$this, 'saveGalleryData'], 24, 3);
        add_action(BASE_ACTION_AFTER_UPDATE_CONTENT, [$this, 'saveGalleryData'], 24, 3);
        add_action(BASE_ACTION_AFTER_DELETE_CONTENT, [$this, 'deleteGalleryMeta'], 55, 2);
        add_action(BASE_ACTION_REGISTER_SITE_MAP, [$this, 'registerSiteMap'], 234, 1);

        if (defined('LANGUAGE_FILTER_MODEL_USING_MULTI_LANGUAGE')) {
            add_filter(LANGUAGE_FILTER_MODEL_USING_MULTI_LANGUAGE, [$this, 'addMultiLanguage'], 60, 1);
        }

        add_shortcode('gallery', __('Gallery images'), __('Add a gallery'), [$this, 'render']);
        shortcode()->setAdminConfig('gallery', view('gallery::partials.short-code-admin-config')->render());
    }

    /**
     * @param $screen
     * @author Sang Nguyen
     */
    public function addGalleryBox($screen)
    {
        if (in_array($screen, $this->screenUsingGallery())) {
            add_meta_box('gallery_wrap', trans('gallery::gallery.gallery_box'), [$this, 'galleryMetaField'], $screen, 'advanced', 'default');
        }
    }
    /**
     * @author Sang Nguyen
     */
    public function galleryMetaField()
    {
        $value = null;
        $args = func_get_args();
        if (!empty($args[0])) {
            $value = gallery_meta_data($args[0]->id, $args[1]);
        }
        return view('gallery::gallery-box', compact('value'))->render();
    }

    /**
     * @param $type
     * @param Request $request
     * @param $object
     * @return mixed
     * @author Sang Nguyen
     */
    public function saveGalleryData($type, Request $request, $object)
    {
        if (in_array($type, $this->screenUsingGallery())) {
            try {
                if (empty($request->input('gallery'))) {
                    app(GalleryMetaInterface::class)->deleteBy(['content_id' => $object->id, 'reference' => $type]);
                    return false;
                }
                $meta = app(GalleryMetaInterface::class)->getFirstBy(['content_id' => $object->id, 'reference' => $type]);
                if (!$meta) {
                    $meta = app(GalleryMetaInterface::class)->getModel();
                    $meta->content_id = $object->id;
                    $meta->reference = $type;
                }

                $meta->images = $request->input('gallery');
                app(GalleryMetaInterface::class)->createOrUpdate($meta);
                return true;
            } catch (Exception $ex) {
                return false;
            }
        }
    }

    /**
     * @param $content
     * @param $screen
     * @return mixed
     * @author Sang Nguyen
     */
    public function deleteGalleryMeta($screen, $content)
    {
        try {
            if (in_array($screen, $this->screenUsingGallery())) {
                app(GalleryMetaInterface::class)->deleteBy(['content_id' => $content->id, 'reference' => $screen]);
            }
            return true;
        } catch (Exception $ex) {
            return false;
        }
    }

    /**
     * @author Sang Nguyen
     * @since 2.3
     */
    public function screenUsingGallery()
    {
        $screen = [GALLERY_MODULE_SCREEN_NAME, PAGE_MODULE_SCREEN_NAME];
        if (defined('POST_MODULE_SCREEN_NAME')) {
            $screen[] = POST_MODULE_SCREEN_NAME;
        }
        return apply_filters(GALLERY_FILTER_SCREEN_USING_GALLERY, $screen);
    }

    /**
     * @param $site_map
     * @return void
     * @author Sang Nguyen
     */
    public function registerSiteMap($site_map)
    {
        $site_map->add(route('public.galleries'), '2016-10-10 00:00', '0.8', 'weekly');
        $galleries = app(GalleryInterface::class)->getDataSiteMap();
        foreach ($galleries as $gallery) {
            $site_map->add(route('public.gallery', $gallery->slug), $gallery->updated_at, '0.8', 'daily');
        }
    }

    /**
     * @param $languages
     * @return array
     * @author Sang Nguyen
     */
    public function addMultiLanguage($languages)
    {
        return array_merge($languages, [GALLERY_MODULE_SCREEN_NAME]);
    }

    /**
     * @param $shortcode
     * @return null
     * @author Sang Nguyen
     */
    public function render($shortcode)
    {
        return render_galleries($shortcode->limit);
    }
}
