<?php

namespace Botble\Blog\Repositories\Eloquent;

use Botble\Support\Repositories\Eloquent\RepositoriesAbstract;
use Botble\Blog\Repositories\Interfaces\MountainsInterface;

class MountainsRepository extends RepositoriesAbstract implements MountainsInterface
{
    /**
     * @param $limit
     * @param array $args
     * @return mixed
     * @author Sang Nguyen
     */
	 
	public function getAllMountains()
	{
		$data = $this->model->orderBy('created_at', 'ASC');
        $data = apply_filters(BASE_FILTER_BEFORE_GET_FRONT_PAGE_ITEM, $data, $this->model, POST_MODULE_SCREEN_NAME)->get();
        $this->resetModel();
        return $data;
    }	
    
    public function getAllActiveMountains()
	{
		$data = $this->model->where('status', 1)->orderBy('created_at', 'ASC');
        $data = apply_filters(BASE_FILTER_BEFORE_GET_FRONT_PAGE_ITEM, $data, $this->model, POST_MODULE_SCREEN_NAME)->get();
        $this->resetModel();
        return $data;
	}	
}
