<?php

namespace Botble\Dashboard\Repositories\Eloquent;

use Botble\Support\Repositories\Eloquent\RepositoriesAbstract;
use Botble\Dashboard\Repositories\Interfaces\DashboardWidgetSettingInterface;

class DashboardWidgetSettingRepository extends RepositoriesAbstract implements DashboardWidgetSettingInterface
{
    /**
     * @return mixed
     * @author Sang Nguyen
     * @since 2.1
     */
    public function getListWidget()
    {
        $data = $this->model->select('id', 'order', 'settings', 'widget_id')
            ->with('widget')
            ->orderBy('order')
            ->where('user_id', '=', acl_get_current_user_id())
            ->get();
        $this->resetModel();
        return $data;
    }
}