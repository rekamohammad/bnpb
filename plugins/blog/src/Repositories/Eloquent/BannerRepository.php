<?php

namespace Botble\Blog\Repositories\Eloquent;

use Botble\Support\Repositories\Eloquent\RepositoriesAbstract;
use Botble\Blog\Repositories\Interfaces\BannerInterface;

class BannerRepository extends RepositoriesAbstract implements BannerInterface
{

    
    public function getListBanner($limit)
    {
        
        $data = $this->model->where('banners.status', '=', 1)
            ->select('banners.*')
            ->orderBy('banners.created_at', 'desc')->limit($limit);
        $data = apply_filters(BASE_FILTER_BEFORE_GET_FRONT_PAGE_ITEM, $data, $this->model, POST_MODULE_SCREEN_NAME)->get();
        $this->resetModel();
        return $data;
    }

}
