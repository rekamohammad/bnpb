<?php

namespace Botble\Page\Repositories\Eloquent;

use Botble\Support\Repositories\Eloquent\RepositoriesAbstract;
use Botble\Page\Repositories\Interfaces\PageInterface;

class PageRepository extends RepositoriesAbstract implements PageInterface
{

    /**
     * @param $name
     * @param $id
     * @return mixed
     * @author Sang Nguyen
     */
    public function createSlug($name, $id)
    {
        // $slug = str_slug($name);
        $slug = $name;
        $index = 1;
        $baseSlug = $slug;
        while ($this->model->whereSlug($slug)->where('id', '!=', $id)->count() > 0) {
            $slug = $baseSlug . '-' . $index++;
        }

        if (empty($slug)) {
            $slug = time();
        }

        $this->resetModel();

        return $slug;
    }

    /**
     * @param $slug
     * @param $status
     * @return mixed
     * @author Sang Nguyen
     */
    public function getBySlug($slug, $status)
    {
        $data = $this->model->where(['pages.status' => $status, 'pages.slug' => $slug])
            ->select('pages.*')->first();
        $data = apply_filters(BASE_FILTER_BEFORE_GET_BY_SLUG, $data, $this->model, 'page');
        $this->resetModel();
        return $data;
    }

    /**
     * @return mixed
     * @author Sang Nguyen
     */
    public function getDataSiteMap()
    {
        $data = $this->model->where('pages.status', 1)
            ->select('pages.*')
            ->orderBy('pages.created_at', 'desc');
        $data = apply_filters(BASE_FILTER_BEFORE_GET_FRONT_PAGE_ITEM, $data, $this->model, PAGE_MODULE_SCREEN_NAME)->get();
        $this->resetModel();
        return $data;
    }

    /**
     * @param $limit
     * @author Sang Nguyen
     */
    public function getFeaturedPages($limit)
    {
        $data = $this->model->where(['pages.status' => 1, 'pages.featured' => 1])
            ->orderBy('pages.order', 'asc')
            ->select('pages.*')
            ->limit($limit)
            ->orderBy('pages.created_at', 'desc');
        $data = apply_filters(BASE_FILTER_BEFORE_GET_FRONT_PAGE_ITEM, $data, $this->model, PAGE_MODULE_SCREEN_NAME)->get();
        $this->resetModel();
        return $data;
    }

    /**
     * @param $array
     * @param array $select
     * @return mixed
     * @author Sang Nguyen
     */
    public function whereIn($array, $select = [])
    {
        $pages = $this->model->whereIn('pages.id', $array);
        if (empty($select)) {
            $select = 'pages.*';
        }
        $data = $pages->select($select)->orderBy('pages.order', 'ASC');
        $data = apply_filters(BASE_FILTER_BEFORE_GET_FRONT_PAGE_ITEM, $data, $this->model, PAGE_MODULE_SCREEN_NAME)->get();
        $this->resetModel();
        return $data;
    }

    /**
     * @param $query
     * @param int $limit
     * @return mixed
     * @author Sang Nguyen
     */
    public function getSearch($query, $limit = 10)
    {
        $pages = $this->model->where('pages.status', 1);
        foreach (explode(' ', $query) as $term) {
            $pages = $pages->where('pages.name', 'LIKE', '%' . $term . '%');
        }

        $data = $pages->select('pages.*')->orderBy('pages.created_at', 'desc')
            ->limit($limit);
        $data = apply_filters(BASE_FILTER_BEFORE_GET_FRONT_PAGE_ITEM, $data, $this->model, PAGE_MODULE_SCREEN_NAME)->get();
        $this->resetModel();
        return $data;
    }

    /**
     * @param bool $active
     * @return mixed
     * @author Sang Nguyen
     */
    public function getAllPages($active = true)
    {
        $data = $this->model->select('pages.*');
        if ($active) {
            $data = $data->where(['pages.status' => 1]);
        }

        $data = $data->get();
        $this->resetModel();
        return $data;
    }
}
