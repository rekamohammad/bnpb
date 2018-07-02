<?php

namespace Botble\Blog\Repositories\Interfaces;

use Botble\Support\Repositories\Interfaces\RepositoryInterface;

interface SliderInterface extends RepositoryInterface
{
    
    /**
     * @param bool $active
     * @return mixed
     * @author Sang Nguyen
     */
    public function getListSlider($limit);
}
