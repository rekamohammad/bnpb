<?php

namespace Botble\Gallery\Repositories\Eloquent;

use Botble\Support\Repositories\Eloquent\RepositoriesAbstract;
use Botble\Gallery\Repositories\Interfaces\GalleryInterface;

class GalleryRepository extends RepositoriesAbstract implements GalleryInterface
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
     * Get all galleries.c
     *
     * @return mixed
     * @author Sang Nguyen
     */
    public function getAll()
    {
        $data = $this->model->where('galleries.status', '=', 1);

        $data = apply_filters(BASE_FILTER_BEFORE_GET_FRONT_PAGE_ITEM, $data, $this->model, GALLERY_MODULE_SCREEN_NAME)->get();
        $this->resetModel();
        return $data;
    }

    /**
     * @param $slug
     * @param $status
     * @return mixed
     * @author Sang Nguyen
     */
    public function getBySlug($slug, $status)
    {
        $data = $this->model->where(['galleries.status' => 1, 'galleries.slug' => $slug])
            ->select('galleries.*')->first();
        $data = apply_filters(BASE_FILTER_BEFORE_GET_BY_SLUG, $data, $this->model, GALLERY_MODULE_SCREEN_NAME);
        $this->resetModel();
        return $data;
    }

    /**
     * @return mixed
     * @author Sang Nguyen
     */
    public function getDataSiteMap()
    {
        $data = $this->model->where('galleries.status', '=', 1)
            ->select('galleries.*')
            ->orderBy('galleries.created_at', 'desc');
        $data = apply_filters(BASE_FILTER_BEFORE_GET_FRONT_PAGE_ITEM, $data, $this->model, GALLERY_MODULE_SCREEN_NAME)->get();
        $this->resetModel();
        return $data;
    }

    /**
     * @param $limit
     * @return mixed
     * @author Sang Nguyen
     */
    public function getFeaturedGalleries($limit)
    {
        $data = $this->model->where(['galleries.status' => 1, 'galleries.featured' => 1])
            ->select('galleries.id', 'galleries.name', 'galleries.slug', 'galleries.user_id', 'galleries.image')
            ->orderBy('galleries.order', 'asc')
            ->limit($limit);
        $data = apply_filters(BASE_FILTER_BEFORE_GET_FRONT_PAGE_ITEM, $data, $this->model, GALLERY_MODULE_SCREEN_NAME)->get();
        $this->resetModel();
        return $data;
    }
}
