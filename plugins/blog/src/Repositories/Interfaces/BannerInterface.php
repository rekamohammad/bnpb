<?php

namespace Botble\Blog\Repositories\Interfaces;

use Botble\Support\Repositories\Interfaces\RepositoryInterface;

interface BannerInterface extends RepositoryInterface
{
    
    /**
     * @param bool $status
     * @return mixed
     * @author Sang Nguyen
     */
    public function getListBanner($limit);
}
