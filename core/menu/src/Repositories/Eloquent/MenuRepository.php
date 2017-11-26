<?php

namespace Botble\Menu\Repositories\Eloquent;

use Botble\Menu\Repositories\Interfaces\MenuInterface;
use Botble\Support\Repositories\Eloquent\RepositoriesAbstract;

class MenuRepository extends RepositoriesAbstract implements MenuInterface
{

    /**
     * @param $slug
     * @param $active
     * @param array $selects
     * @return mixed
     * @author Sang Nguyen
     */
    public function findBySlug($slug, $active, $selects = [])
    {
        $data = $this->model->where('menus.slug', '=', $slug);
        if ($active) {
            $data = $data->where('menus.status', '=', 1)->select($selects);
        }
        $data = $data->first();
        if ($active) {
            return apply_filters(BASE_FILTER_BEFORE_GET_BY_SLUG, $data, $this->model, MENU_MODULE_SCREEN_NAME);
        }
        $this->resetModel();
        return $data;
    }

    /**
     * @param $name
     * @return mixed
     * @author Sang Nguyen
     */
    public function createSlug($name)
    {
        $slug = str_slug($name);
        $index = 1;
        $baseSlug = $slug;
        while ($this->model->whereSlug($slug)->count() > 0) {
            $slug = $baseSlug . '-' . $index++;
        }

        if (empty($slug)) {
            $slug = time();
        }

        $this->resetModel();

        return $slug;
    }
}
