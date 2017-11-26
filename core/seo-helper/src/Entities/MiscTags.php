<?php

namespace Botble\SeoHelper\Entities;

use Botble\SeoHelper\Contracts\Entities\MiscTagsContract;

class MiscTags implements MiscTagsContract
{

    /**
     * Current URL.
     *
     * @var string
     */
    protected $currentUrl = '';

    /**
     * Meta collection.
     *
     * @var \Botble\SeoHelper\Contracts\Entities\MetaCollectionContract
     */
    protected $meta;

    /**
     * Make MiscTags instance.
     * @author ARCANEDEV <arcanedev.maroc@gmail.com>
     */
    public function __construct()
    {
        $this->meta = new MetaCollection;
        $this->addCanonical();
        $this->addMany(config('seo-helper.misc.default', []));
    }

    /**
     * Get the current URL.
     *
     * @return string
     * @author ARCANEDEV <arcanedev.maroc@gmail.com>
     */
    public function getUrl()
    {
        return $this->currentUrl;
    }

    /**
     * Set the current URL.
     *
     * @param  string $url
     *
     * @return \Botble\SeoHelper\Entities\MiscTags
     * @author ARCANEDEV <arcanedev.maroc@gmail.com>
     */
    public function setUrl($url)
    {
        $this->currentUrl = $url;
        $this->addCanonical();

        return $this;
    }

    /**
     * Make MiscTags instance.
     *
     * @param  array $defaults
     *
     * @return \Botble\SeoHelper\Entities\MiscTags
     * @author ARCANEDEV <arcanedev.maroc@gmail.com>
     */
    public static function make(array $defaults = [])
    {
        return new self([
            'default' => $defaults,
        ]);
    }

    /**
     * Add a meta tag.
     *
     * @param  string $name
     * @param  string $content
     *
     * @return \Botble\SeoHelper\Entities\MiscTags
     * @author ARCANEDEV <arcanedev.maroc@gmail.com>
     */
    public function add($name, $content)
    {
        $this->meta->add($name, $content);

        return $this;
    }

    /**
     * Add many meta tags.
     *
     * @param  array $meta
     *
     * @return \Botble\SeoHelper\Entities\MiscTags
     * @author ARCANEDEV <arcanedev.maroc@gmail.com>
     */
    public function addMany(array $meta)
    {
        $this->meta->addMany($meta);

        return $this;
    }

    /**
     * Remove a meta from the meta collection by key.
     *
     * @param  array|string $names
     *
     * @return \Botble\SeoHelper\Entities\MiscTags
     * @author ARCANEDEV <arcanedev.maroc@gmail.com>
     */
    public function remove($names)
    {
        $this->meta->remove($names);

        return $this;
    }

    /**
     * Reset the meta collection.
     *
     * @return \Botble\SeoHelper\Entities\MiscTags
     * @author ARCANEDEV <arcanedev.maroc@gmail.com>
     */
    public function reset()
    {
        $this->meta->reset();

        return $this;
    }

    /**
     * Render the tag.
     *
     * @return string
     * @author ARCANEDEV <arcanedev.maroc@gmail.com>
     */
    public function render()
    {
        return $this->meta->render();
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
     * Check if has the current URL.
     *
     * @return bool
     * @author ARCANEDEV <arcanedev.maroc@gmail.com>
     */
    private function hasUrl()
    {
        return !empty($this->getUrl());
    }

    /**
     * Add the canonical link.
     *
     * @return \Botble\SeoHelper\Entities\MiscTags
     * @author ARCANEDEV <arcanedev.maroc@gmail.com>
     */
    private function addCanonical()
    {
        if ($this->hasUrl()) {
            $this->add('canonical', $this->currentUrl);
        }

        return $this;
    }
}
