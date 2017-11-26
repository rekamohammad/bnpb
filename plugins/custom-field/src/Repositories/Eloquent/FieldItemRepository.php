<?php

namespace Botble\CustomField\Repositories\Eloquent;

use Botble\Support\Repositories\Eloquent\RepositoriesAbstract;
use Botble\CustomField\Repositories\Interfaces\FieldItemInterface;

class FieldItemRepository extends RepositoriesAbstract implements FieldItemInterface
{

    /**
     * @param int $id
     * @param int $fieldGroupId
     * @param int $parentId
     * @param string $slug
     * @return string
     */
    public function makeUniqueSlug($id, $fieldGroupId, $parentId, $slug)
    {
        $isExist = $this->model->where([
            'slug' => $slug,
            'field_group_id' => $fieldGroupId,
            'parent_id' => $parentId,
        ])->first();

        if ($isExist && (int)$id != (int)$isExist->id) {
            return $slug . '_' . time();
        }
        $this->resetModel();
        return $slug;
    }
}
