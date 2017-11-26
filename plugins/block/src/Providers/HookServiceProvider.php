<?php

namespace Botble\Block\Providers;

use Botble\Block\Repositories\Interfaces\BlockInterface;
use Illuminate\Support\ServiceProvider;

class HookServiceProvider extends ServiceProvider
{

    /**
     * @author Sang Nguyen
     */
    public function boot()
    {
        if (defined('LANGUAGE_FILTER_MODEL_USING_MULTI_LANGUAGE')) {
            add_filter(LANGUAGE_FILTER_MODEL_USING_MULTI_LANGUAGE, [$this, 'addMultiLanguage'], 70, 1);
        }

        add_shortcode('static-block', __('Static Block'), __('Add a custom static block'), [$this, 'render']);
        //shortcode()->setAdminConfig('static-block', view('block::partials.short-code-admin-config')->render());
    }

    /**
     * @param $languages
     * @return array
     * @author Sang Nguyen
     */
    public function addMultiLanguage($languages)
    {
        return array_merge($languages, [BLOCK_MODULE_SCREEN_NAME]);
    }

    /**
     * @param $shortcode
     * @return null
     * @author Sang Nguyen
     */
    public function render($shortcode)
    {
        $block = app(BlockInterface::class)->getFirstBy([
            'alias' => $shortcode->alias,
            'status' => 1,
        ]);

        if (empty($block)) {
            return null;
        }

        return $block->content;
    }
}
