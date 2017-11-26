<?php

namespace Botble\Widget\Repositories\Eloquent;

use Botble\Support\Repositories\Eloquent\RepositoriesAbstract;
use Botble\Widget\Repositories\Interfaces\WidgetInterface;

class WidgetRepository extends RepositoriesAbstract implements WidgetInterface
{
    /**
     * Get all theme widgets
     * @return mixed
     * @author Sang Nguyen
     */
    public function getByTheme()
    {
        $data = $this->model->where('theme', '=', setting('theme'))->get();
        $this->resetModel();
        return $data;
    }
}
