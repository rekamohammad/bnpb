<?php

namespace Botble\SeoHelper\Providers;

use Illuminate\Support\ServiceProvider;
use Exception;
use Illuminate\Http\Request;
use SeoHelper;

class HookServiceProvider extends ServiceProvider
{

    /**
     * Boot the service provider.
     * @author Sang Nguyen
     */
    public function boot()
    {
        add_action(BASE_ACTION_META_BOXES, [$this, 'addMetaBox'], 12, 3);
        add_action(BASE_ACTION_AFTER_CREATE_CONTENT, [$this, 'saveMetaData'], 20, 3);
        add_action(BASE_ACTION_AFTER_UPDATE_CONTENT, [$this, 'saveMetaData'], 20, 3);
        add_action(BASE_ACTION_AFTER_DELETE_CONTENT, [$this, 'deleteMetaData'], 55, 2);
        add_action(BASE_ACTION_PUBLIC_RENDER_SINGLE, [$this, 'setSeoMeta'], 56, 2);
    }

    /**
     * @param $screen
     * @author Sang Nguyen
     */
    public function addMetaBox($screen)
    {
        if (in_array($screen, SeoHelper::screenUsingSEOHelper())) {
            add_meta_box('seo_wrap', trans('seo-helper::seo-helper.meta_box_header'), [$this, 'seoMetaBox'], $screen);
        }
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @author Sang Nguyen
     */
    public function seoMetaBox()
    {
        $meta = [
            'seo_title' => null,
            'seo_keyword' => null,
            'seo_description' => null,
        ];

        $args = func_get_args();
        if (!empty($args[0])) {
            $meta_data = get_meta_data($args[0]->id, 'seo_meta', $args[1], true);
        }

        if (!empty($meta_data) && is_array($meta_data)) {
            $meta = array_merge($meta, $meta_data);
        }

        return view('seo-helper::meta_box', compact('meta'));
    }

    /**
     * @param $screen
     * @param Request $request
     * @param $object
     * @return bool
     * @author Sang Nguyen
     */
    public function saveMetaData($screen, Request $request, $object)
    {
        if (in_array($screen, SeoHelper::screenUsingSEOHelper())) {
            try {
                if (empty($request->input('seo_meta'))) {
                    delete_meta_data($object->id, 'seo_meta', $screen);
                    return false;
                }
                save_meta_data($object->id, 'seo_meta', $request->input('seo_meta'), $screen);
                return true;
            } catch (Exception $ex) {
                return false;
            }
        }
    }

    /**
     * @param $screen
     * @param $object
     * @return bool
     * @author Sang Nguyen
     */
    public function deleteMetaData($screen, $object)
    {
        try {
            if (in_array($screen, SeoHelper::screenUsingSEOHelper())) {
                delete_meta_data($object->id, 'seo_meta', $screen);
            }
            return true;
        } catch (Exception $ex) {
            return false;
        }
    }

    /**
     * @param $screen
     * @param $object
     * @author Sang Nguyen
     */
    public function setSeoMeta($screen, $object)
    {
        $meta = get_meta_data($object->id, 'seo_meta', $screen, true);
        if (!empty($meta)) {
            if (!empty($meta['seo_title'])) {
                SeoHelper::setTitle($meta['seo_title']);
            }

            if (!empty($meta['seo_keyword'])) {
                SeoHelper::setKeywords($meta['seo_keyword']);
            }

            if (!empty($meta['seo_description'])) {
                SeoHelper::setDescription($meta['seo_description']);
            }
        }
    }
}
