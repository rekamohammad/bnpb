<?php

namespace Botble\ACL\Repositories\Eloquent;

use Botble\ACL\Repositories\Interfaces\PermissionInterface;
use Botble\Support\Repositories\Eloquent\RepositoriesAbstract;

/**
 * Class PermissionRepository
 * @package Botble\ACL\Repositories
 */
class PermissionRepository extends RepositoriesAbstract implements PermissionInterface
{
    /**
     * @param array $select
     * @return mixed
     * @author Sang Nguyen
     */
    public function getVisibleFeatures(array $select = ['*'])
    {
        $data = $this->model->orderBy('name')
            ->whereIsFeature(1)
            ->whereFeatureVisible(1)
            ->select($select)
            ->get();
        $this->resetModel();

        return $data;
    }

    /**
     * @param array $select
     * @return mixed
     * @author Sang Nguyen
     */
    public function getVisiblePermissions(array $select = ['*'])
    {
        $data = $this->model->orderBy('name')
            ->whereFeatureVisible(1)
            ->select($select)
            ->get();
        $this->resetModel();
        return $data;
    }
}
