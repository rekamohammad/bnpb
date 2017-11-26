<?php

namespace Botble\SeoHelper;

use Botble\SeoHelper\Contracts\SeoHelperContract;
use Botble\SeoHelper\Contracts\SeoMetaContract;
use Botble\SeoHelper\Contracts\SeoOpenGraphContract;
use Botble\SeoHelper\Contracts\SeoTwitterContract;

class SeoHelper implements SeoHelperContract
{
    /**
     * The SeoMeta instance.
     *
     * @var \Botble\SeoHelper\Contracts\SeoMetaContract
     */
    private $seoMeta;

    /**
     * The SeoOpenGraph instance.
     *
     * @var \Botble\SeoHelper\Contracts\SeoOpenGraphContract
     */
    private $seoOpenGraph;

    /**
     * The SeoTwitter instance.
     *
     * @var \Botble\SeoHelper\Contracts\SeoTwitterContract
     */
    private $seoTwitter;

    /**
     * Make SeoHelper instance.
     *
     * @param  \Botble\SeoHelper\Contracts\SeoMetaContract $seoMeta
     * @param  \Botble\SeoHelper\Contracts\SeoOpenGraphContract $seoOpenGraph
     * @param  \Botble\SeoHelper\Contracts\SeoTwitterContract $seoTwitter
     * @author ARCANEDEV <arcanedev.maroc@gmail.com>
     */
    public function __construct(
        SeoMetaContract $seoMeta,
        SeoOpenGraphContract $seoOpenGraph,
        SeoTwitterContract $seoTwitter
    )
    {
        $this->setSeoMeta($seoMeta);
        $this->setSeoOpenGraph($seoOpenGraph);
        $this->setSeoTwitter($seoTwitter);
    }

    /**
     * Get SeoMeta instance.
     *
     * @return \Botble\SeoHelper\Contracts\SeoMetaContract
     * @author ARCANEDEV <arcanedev.maroc@gmail.com>
     */
    public function meta()
    {
        return $this->seoMeta;
    }

    /**
     * Set SeoMeta instance.
     *
     * @param  \Botble\SeoHelper\Contracts\SeoMetaContract $seoMeta
     *
     * @return \Botble\SeoHelper\SeoHelper
     * @author ARCANEDEV <arcanedev.maroc@gmail.com>
     */
    public function setSeoMeta(SeoMetaContract $seoMeta)
    {
        $this->seoMeta = $seoMeta;

        return $this;
    }

    /**
     * Get SeoOpenGraph instance.
     *
     * @return \Botble\SeoHelper\Contracts\SeoOpenGraphContract
     * @author ARCANEDEV <arcanedev.maroc@gmail.com>
     */
    public function openGraph()
    {
        return $this->seoOpenGraph;
    }

    /**
     * Get SeoOpenGraph instance.
     *
     * @param  \Botble\SeoHelper\Contracts\SeoOpenGraphContract $seoOpenGraph
     *
     * @return \Botble\SeoHelper\SeoHelper
     * @author ARCANEDEV <arcanedev.maroc@gmail.com>
     */
    public function setSeoOpenGraph(SeoOpenGraphContract $seoOpenGraph)
    {
        $this->seoOpenGraph = $seoOpenGraph;

        return $this;
    }

    /**
     * Get SeoTwitter instance.
     *
     * @return \Botble\SeoHelper\Contracts\SeoTwitterContract
     * @author ARCANEDEV <arcanedev.maroc@gmail.com>
     */
    public function twitter()
    {
        return $this->seoTwitter;
    }

    /**
     * Set SeoTwitter instance.
     *
     * @param  \Botble\SeoHelper\Contracts\SeoTwitterContract $seoTwitter
     *
     * @return \Botble\SeoHelper\SeoHelper
     * @author ARCANEDEV <arcanedev.maroc@gmail.com>
     */
    public function setSeoTwitter(SeoTwitterContract $seoTwitter)
    {
        $this->seoTwitter = $seoTwitter;

        return $this;
    }

    /**
     * Set title.
     *
     * @param  string $title
     * @param  string|null $siteName
     * @param  string|null $separator
     *
     * @return \Botble\SeoHelper\SeoHelper
     * @author ARCANEDEV <arcanedev.maroc@gmail.com>
     */
    public function setTitle($title, $siteName = null, $separator = null)
    {
        $this->meta()->setTitle($title, $siteName, $separator);
        $this->openGraph()->setTitle($title);
        $this->openGraph()->setSiteName($siteName);
        $this->twitter()->setTitle($title);

        return $this;
    }

    /**
     * Set description.
     *
     * @param  string $description
     *
     * @return \Botble\SeoHelper\Contracts\SeoHelperContract
     * @author ARCANEDEV <arcanedev.maroc@gmail.com>
     */
    public function setDescription($description)
    {
        $this->meta()->setDescription($description);
        $this->openGraph()->setDescription($description);
        $this->twitter()->setDescription($description);

        return $this;
    }

    /**
     * Set keywords.
     *
     * @param  array|string $keywords
     *
     * @return \Botble\SeoHelper\SeoHelper
     * @author ARCANEDEV <arcanedev.maroc@gmail.com>
     */
    public function setKeywords($keywords)
    {
        $this->meta()->setKeywords($keywords);

        return $this;
    }

    /**
     * Render all seo tags.
     *
     * @return string
     * @author ARCANEDEV <arcanedev.maroc@gmail.com>
     */
    public function render()
    {
        return implode(PHP_EOL, array_filter([
            $this->meta()->render(),
            $this->openGraph()->render(),
            $this->twitter()->render(),
        ]));
    }

    /**
     * Render the tag.
     *
     * @return string
     * @author ARCANEDEV <arcanedev.maroc@gmail.com>
     */
    public function __toString()
    {
        return $this->render();
    }

    /**
     * @author Sang Nguyen
     * @since 2.3
     */
    public function screenUsingSEOHelper()
    {
        $default = [PAGE_MODULE_SCREEN_NAME];
        if (defined('POST_MODULE_SCREEN_NAME')) {
            $default[] = POST_MODULE_SCREEN_NAME;
        }
        if (defined('CATEGORY_MODULE_SCREEN_NAME')) {
            $default[] = CATEGORY_MODULE_SCREEN_NAME;
        }
        if (defined('TAG_MODULE_SCREEN_NAME')) {
            $default[] = TAG_MODULE_SCREEN_NAME;
        }
        return apply_filters(SEO_HELPER_FILTER_SCREEN_USING, $default);
    }
}
