<?php

namespace Botble\Base\Supports;

class PageTitle
{
    protected $title;

    /**
     * @param $title
     * @author Sang Nguyen
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }

    /**
     * @param bool $full
     * @return string
     * @author Sang Nguyen
     */
    public function getTitle($full = true)
    {
        if (empty($this->title)) {
            return config('cms.base_name');
        }

        if (!$full) {
            return $this->title;
        }
        
        return $this->title . ' | ' . config('cms.base_name');
    }
}