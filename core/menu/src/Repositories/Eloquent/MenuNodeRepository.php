<?php

namespace Botble\Menu\Repositories\Eloquent;

use Botble\Support\Repositories\Eloquent\RepositoriesAbstract;
use Botble\Menu\Repositories\Interfaces\MenuNodeInterface;

class MenuNodeRepository extends RepositoriesAbstract implements MenuNodeInterface
{
    /**
     * @param $menu_content_id
     * @param $parent_id
     * @param array $selects
     * @return array|\Illuminate\Database\Eloquent\Collection|static[]
     * @author Sang Nguyen
     */
    public function getByMenuContentId($menu_content_id, $parent_id, $select = ['*'])
    {
        $data = $this->model->where(['menu_content_id' => $menu_content_id, 'parent_id' => $parent_id])
            ->select($select)
            ->orderBy('position', 'asc')->get();
        $this->resetModel();
        return $data;
    }
}
