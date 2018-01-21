<?php

namespace Botble\Blog\Repositories\Eloquent;

use Botble\Support\Repositories\Eloquent\RepositoriesAbstract;
use Botble\Blog\Repositories\Interfaces\KabupatenInterface;

class KabupatenRepository extends RepositoriesAbstract implements KabupatenInterface
{

    /**
     * @return mixed
     * @author Sang Nguyen
     */
    public function getDataSiteMap()
    {
        $data = $this->model->where('links.status', '=', 1)
            ->select('links.*')
            ->orderBy('links.created_at', 'desc');
        $data = apply_filters(BASE_FILTER_BEFORE_GET_FRONT_PAGE_ITEM, $data, $this->model, POST_MODULE_SCREEN_NAME)->get();
        $this->resetModel();
        return $data;
    }

    /**
     * @param $query
     * @param int $limit
     * @param int $paginate
     * @return mixed
     * @author Sang Nguyen
     */
    public function getSearch($query, $limit = 10, $paginate = 10)
    {
        $posts = $this->model->whereStatus(1);
        foreach (explode(' ', $query) as $term) {
            $posts = $posts->where('name', 'LIKE', '%' . $term . '%');
        }

        $data = $posts->select('links.*')
            ->orderBy('links.created_at', 'desc');
        if ($limit) {
            $data = $data->limit($limit);
        }

        if ($paginate) {
            $data = apply_filters(BASE_FILTER_BEFORE_GET_FRONT_PAGE_ITEM, $data, $this->model, POST_MODULE_SCREEN_NAME)->paginate($paginate);
        } else {
            $data = apply_filters(BASE_FILTER_BEFORE_GET_FRONT_PAGE_ITEM, $data, $this->model, POST_MODULE_SCREEN_NAME)->get();
        }
        $this->resetModel();
        return $data;
    }

    public function getListLinks()
    {
        $data = $this->model->orderBy('links.created_at', 'DESC');
        return apply_filters(BASE_FILTER_BEFORE_GET_FRONT_PAGE_ITEM, $data, $this->model, POST_MODULE_SCREEN_NAME)->get();
    }

    /**
     * @param $limit
     * @param array $args
     * @return mixed
     * @author Sang Nguyen
     */
}
