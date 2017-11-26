<?php

namespace Botble\CustomField\Repositories\Interfaces;

use Botble\Support\Repositories\Interfaces\RepositoryInterface;

interface FieldGroupInterface extends RepositoryInterface
{
    /**
     * @param int $groupId
     * @param null $parentId
     * @return mixed
     */
    public function getGroupItems($groupId, $parentId = null);

    /**
     * @param $groupId
     * @param null|int $parentId
     * @param bool $withValue
     * @param null|string $morphClass
     * @param null|int $morphId
     * @return array
     */
    public function getFieldGroupItems($groupId, $parentId = null, $withValue = false, $morphClass = null, $morphId = null);
}
