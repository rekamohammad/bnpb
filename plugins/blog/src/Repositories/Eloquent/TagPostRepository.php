<?php

namespace Botble\Blog\Repositories\Eloquent;

use Botble\Support\Repositories\Eloquent\RepositoriesAbstract;
use Botble\Blog\Repositories\Interfaces\TagPostInterface;

class TagPostRepository extends RepositoriesAbstract implements TagPostInterface
{


	 public function getRelatedTags($idTag,$limit)
	 {
		 
		 $data = $this->model->where('post_tag.tag_id',$idTag)
            ->select('post_tag.*')
            ->limit($limit); 
		$data = apply_filters(BASE_FILTER_BEFORE_GET_FRONT_PAGE_ITEM, $data, $this->model, POST_MODULE_SCREEN_NAME)->get();
        $this->resetModel();
        return $data;
	 }
}
