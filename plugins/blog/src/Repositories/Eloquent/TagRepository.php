<?php

namespace Botble\Blog\Repositories\Eloquent;

use Botble\Support\Repositories\Eloquent\RepositoriesAbstract;
use Botble\Blog\Repositories\Interfaces\TagInterface;

class TagRepository extends RepositoriesAbstract implements TagInterface
{

    /**
     * @param $name
     * @param $id
     * @return mixed
     * @author Sang Nguyen
     */
    public function createSlug($name, $id)
    {
        $slug = str_slug($name);
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
        $data = $this->model->where(['tags.status' => $status, 'tags.slug' => $slug])
            ->select('tags.*')->first();
        $data = apply_filters(BASE_FILTER_BEFORE_GET_BY_SLUG, $data, $this->model, TAG_MODULE_SCREEN_NAME);
        $this->resetModel();
        return $data;
    }

    /**
     * @return mixed
     * @author Sang Nguyen
     */
    public function getDataSiteMap()
    {
        $data = $this->model->where('tags.status', '=', 1)
            ->select('tags.*')
            ->orderBy('tags.created_at', 'desc');
        $data = apply_filters(BASE_FILTER_BEFORE_GET_FRONT_PAGE_ITEM, $data, $this->model, TAG_MODULE_SCREEN_NAME)->get();
        $this->resetModel();
        return $data;
    }

    /**
     * @param $limit
     * @return mixed
     * @author Sang Nguyen
     */
    public function getPopularTags($limit)
    {
        $data = $this->model->orderBy('tags.id', 'DESC')
            ->select('tags.*')
            ->limit($limit);
        $data = apply_filters(BASE_FILTER_BEFORE_GET_FRONT_PAGE_ITEM, $data, $this->model, TAG_MODULE_SCREEN_NAME)->get();
        $this->resetModel();
        return $data;
    }

    /**
     * @param bool $active
     * @return mixed
     * @author Sang Nguyen
     */
    public function getAllTags($active = true)
    {
        $data = $this->model->select('tags.*');
        if ($active) {
            $data = $data->where(['tags.status' => 1]);
        }

        $data = apply_filters(BASE_FILTER_BEFORE_GET_FRONT_PAGE_ITEM, $data, $this->model, TAG_MODULE_SCREEN_NAME)
            ->get();
        $this->resetModel();
        return $data;
    }
}
