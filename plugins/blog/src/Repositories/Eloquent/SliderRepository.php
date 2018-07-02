<?php

namespace Botble\Blog\Repositories\Eloquent;

use Botble\Support\Repositories\Eloquent\RepositoriesAbstract;
use Botble\Blog\Repositories\Interfaces\SliderInterface;

class SliderRepository extends RepositoriesAbstract implements SliderInterface
{

    
    public function getListSlider($limit)
    {
        
        $data = $this->model->where('sliders.status', '=', 1)
            ->select('sliders.*')
            ->orderBy('sliders.created_at', 'desc')->limit($limit);;
        $data = apply_filters(BASE_FILTER_BEFORE_GET_FRONT_PAGE_ITEM, $data, $this->model, POST_MODULE_SCREEN_NAME)->get();
        $this->resetModel();
        return $data;
    }

}
