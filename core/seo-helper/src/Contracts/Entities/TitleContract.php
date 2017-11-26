<?php

namespace Botble\SeoHelper\Contracts\Entities;

use Botble\SeoHelper\Contracts\RenderableContract;

interface TitleContract extends RenderableContract
{
    /**
     * Get title only (without site name or separator).
     *
     * @return string
     * @author ARCANEDEV <arcanedev.maroc@gmail.com>
     */
    public function getTitleOnly();

    /**
     * Set title.
     *
     * @param  string $title
     *
     * @return self
     * @author ARCANEDEV <arcanedev.maroc@gmail.com>
     */
    public function set($title);

    /**
     * Get site name.
     *
     * @return string
     * @author ARCANEDEV <arcanedev.maroc@gmail.com>
     */
    public function getSiteName();

    /**
     * Set site name.
     *
     * @param  string $siteName
     *
     * @return self
     * @author ARCANEDEV <arcanedev.maroc@gmail.com>
     */
    public function setSiteName($siteName);

    /**
     * Get title separator.
     *
     * @return string
     * @author ARCANEDEV <arcanedev.maroc@gmail.com>
     */
    public function getSeparator();

    /**
     * Set title separator.
     *
     * @param  string $separator
     *
     * @return self
     * @author ARCANEDEV <arcanedev.maroc@gmail.com>
     */
    public function setSeparator($separator);

    /**
     * Set title first.
     *
     * @return self
     * @author ARCANEDEV <arcanedev.maroc@gmail.com>
     */
    public function setFirst();

    /**
     * Set title last.
     *
     * @return self
     * @author ARCANEDEV <arcanedev.maroc@gmail.com>
     */
    public function setLast();

    /**
     * Check if title is first.
     *
     * @return bool
     */
    public function isTitleFirst();

    /**
     * Get title max lenght.
     *
     * @return int
     * @author ARCANEDEV <arcanedev.maroc@gmail.com>
     */
    public function getMax();

    /**
     * Set title max lenght.
     *
     * @param  int $max
     *
     * @return self
     * @author ARCANEDEV <arcanedev.maroc@gmail.com>
     */
    public function setMax($max);

    /**
     * Make a Title instance.
     *
     * @param  string $title
     * @param  string $siteName
     * @param  string $separator
     *
     * @return self
     * @author ARCANEDEV <arcanedev.maroc@gmail.com>
     */
    public static function make($title, $siteName = '', $separator = '-');
}
