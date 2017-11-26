<?php

namespace Botble\Block\Repositories\Eloquent;

use Botble\Support\Repositories\Eloquent\RepositoriesAbstract;
use Botble\Block\Repositories\Interfaces\BlockInterface;

class BlockRepository extends RepositoriesAbstract implements BlockInterface
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
        while ($this->model->where('alias', $slug)->where('id', '!=', $id)->count() > 0) {
            $slug = $baseSlug . '-' . $index++;
        }

        if (empty($slug)) {
            $slug = time();
        }

        $this->resetModel();

        return $slug;
    }
}
