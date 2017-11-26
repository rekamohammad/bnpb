<?php

namespace Botble\ACL\Repositories\Interfaces;

use Botble\Support\Repositories\Interfaces\RepositoryInterface;

interface PermissionInterface extends RepositoryInterface
{
    /**
     * @param array $select
     * @return mixed
     * @author Sang Nguyen
     */
    public function getVisibleFeatures(array $select = ['*']);

    /**
     * @param array $select
     * @return mixed
     * @author Sang Nguyen
     */
    public function getVisiblePermissions(array $select = ['*']);
}
