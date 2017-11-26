<?php

namespace Botble\SeoHelper\Entities;

use Botble\SeoHelper\Contracts\Entities\TitleContract;
use Botble\SeoHelper\Exceptions\InvalidArgumentException;

/**
 * Class     Title
 *
 * @package  Botble\SeoHelper\Entities
 */
class Title implements TitleContract
{

    /**
     * The title content.
     *
     * @var string
     */
    protected $title = '';

    /**
     * The site name.
     *
     * @var string
     */
    protected $siteName = '';

    /**
     * The title separator.
     *
     * @var string
     */
    protected $separator = '-';

    /**
     * Display the title first.
     *
     * @var bool
     */
    protected $titleFirst = true;

    /**
     * The maximum title length.
     *
     * @var int
     */
    protected $max = 55;

    /**
     * Make the Title instance.
     *
     * @param  array $configs
     * @author ARCANEDEV <arcanedev.maroc@gmail.com>
     */
    public function __construct(array $configs = [])
    {
        $this->init();
    }

    /**
     * Start the engine.
     * @author ARCANEDEV <arcanedev.maroc@gmail.com>
     */
    private function init()
    {
        $this->set(setting('site_title', ''));
        $this->setSiteName(setting('site_title', ''));
        $this->setSeparator(config('seo-helper.title.separator', '-'));
        $this->switchPosition(config('seo-helper.title.first', true));
        $this->setMax(config('seo-helper.title.max', 55));
    }

    /**
     * Get title only (without site name or separator).
     *
     * @return string
     * @author ARCANEDEV <arcanedev.maroc@gmail.com>
     */
    public function getTitleOnly()
    {
        return $this->title;
    }

    /**
     * Set title.
     *
     * @param  string $title
     *
     * @return \Botble\SeoHelper\Entities\Title
     * @author ARCANEDEV <arcanedev.maroc@gmail.com>
     */
    public function set($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get site name.
     *
     * @return string
     * @author ARCANEDEV <arcanedev.maroc@gmail.com>
     */
    public function getSiteName()
    {
        return $this->siteName;
    }

    /**
     * Set site name.
     *
     * @param  string $siteName
     *
     * @return \Botble\SeoHelper\Entities\Title
     * @author ARCANEDEV <arcanedev.maroc@gmail.com>
     */
    public function setSiteName($siteName)
    {
        $this->siteName = $siteName;

        return $this;
    }

    /**
     * Get title separator.
     *
     * @return string
     * @author ARCANEDEV <arcanedev.maroc@gmail.com>
     */
    public function getSeparator()
    {
        return $this->separator;
    }

    /**
     * Set title separator.
     *
     * @param  string $separator
     *
     * @return \Botble\SeoHelper\Entities\Title
     * @author ARCANEDEV <arcanedev.maroc@gmail.com>
     */
    public function setSeparator($separator)
    {
        $this->separator = trim($separator);

        return $this;
    }

    /**
     * Set title first.
     *
     * @return \Botble\SeoHelper\Entities\Title
     * @author ARCANEDEV <arcanedev.maroc@gmail.com>
     */
    public function setFirst()
    {
        return $this->switchPosition(true);
    }

    /**
     * Set title last.
     *
     * @return \Botble\SeoHelper\Entities\Title
     * @author ARCANEDEV <arcanedev.maroc@gmail.com>
     */
    public function setLast()
    {
        return $this->switchPosition(false);
    }

    /**
     * Switch title position.
     *
     * @param  bool $first
     *
     * @return \Botble\SeoHelper\Entities\Title
     * @author ARCANEDEV <arcanedev.maroc@gmail.com>
     */
    private function switchPosition($first)
    {
        $this->titleFirst = boolval($first);

        return $this;
    }

    /**
     * Check if title is first.
     *
     * @return bool
     * @author ARCANEDEV <arcanedev.maroc@gmail.com>
     */
    public function isTitleFirst()
    {
        return $this->titleFirst;
    }

    /**
     * Get title max length.
     *
     * @return int
     * @author ARCANEDEV <arcanedev.maroc@gmail.com>
     */
    public function getMax()
    {
        return $this->max;
    }

    /**
     * Set title max length.
     *
     * @param  int $max
     *
     * @return \Botble\SeoHelper\Entities\Title
     * @author ARCANEDEV <arcanedev.maroc@gmail.com>
     */
    public function setMax($max)
    {
        $this->checkMax($max);

        $this->max = $max;

        return $this;
    }

    /**
     * Make a Title instance.
     *
     * @param  string $title
     * @param  string $siteName
     * @param  string $separator
     *
     * @return \Botble\SeoHelper\Entities\Title
     * @author ARCANEDEV <arcanedev.maroc@gmail.com>
     */
    public static function make($title, $siteName = '', $separator = '-')
    {
        return new self([
            'default' => $title,
            'site-name' => $siteName,
            'separator' => $separator,
            'first' => true
        ]);
    }

    /**
     * Render the tag.
     *
     * @return string
     * @author ARCANEDEV <arcanedev.maroc@gmail.com>
     */
    public function render()
    {
        $separator = $this->renderSeparator();
        $output = $this->isTitleFirst()
            ? $this->renderTitleFirst($separator)
            : $this->renderTitleLast($separator);

        $output = str_limit(strip_tags($output), $this->getMax());

        return '<title>' . e($output) . '</title>';
    }

    /**
     * Render the separator.
     *
     * @return string
     * @author ARCANEDEV <arcanedev.maroc@gmail.com>
     */
    protected function renderSeparator()
    {
        return empty($separator = $this->getSeparator()) ? ' ' : ' ' . $separator . ' ';
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
     * Check title max length.
     *
     * @param  int $max
     *
     * @throws \Botble\SeoHelper\Exceptions\InvalidArgumentException
     * @author ARCANEDEV <arcanedev.maroc@gmail.com>
     */
    private function checkMax($max)
    {
        if (!is_int($max)) {
            throw new InvalidArgumentException('The title maximum lenght must be integer.');
        }

        if ($max <= 0) {
            throw new InvalidArgumentException('The title maximum lenght must be greater 0.');
        }
    }

    /**
     * Render title first.
     *
     * @param  string $separator
     *
     * @return string
     * @author ARCANEDEV <arcanedev.maroc@gmail.com>
     */
    private function renderTitleFirst($separator)
    {
        $output = [];
        $output[] = $this->getTitleOnly();

        if ($this->hasSiteName()) {
            $output[] = $separator;
            $output[] = $this->getSiteName();
        }

        return implode('', $output);
    }

    /**
     * Render title last.
     *
     * @param  string $separator
     *
     * @return string
     * @author ARCANEDEV <arcanedev.maroc@gmail.com>
     */
    private function renderTitleLast($separator)
    {
        $output = [];

        if ($this->hasSiteName()) {
            $output[] = $this->getSiteName();
            $output[] = $separator;
        }

        $output[] = $this->getTitleOnly();

        return implode('', $output);
    }

    /**
     * Check if site name exists.
     *
     * @return bool
     * @author ARCANEDEV <arcanedev.maroc@gmail.com>
     */
    private function hasSiteName()
    {
        return !empty($this->getSiteName());
    }
}
