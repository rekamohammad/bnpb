<?php

namespace Botble\CustomField\Repositories\Eloquent;

use Botble\Support\Repositories\Eloquent\RepositoriesAbstract;
use Botble\CustomField\Repositories\Interfaces\CustomFieldInterface;
use Botble\CustomField\Repositories\Interfaces\FieldGroupInterface;
use Botble\CustomField\Repositories\Interfaces\FieldItemInterface;
use Botble\Media\Repositories\Interfaces\MediaFileInterface;

class FieldGroupRepository extends RepositoriesAbstract implements FieldGroupInterface
{

    /**
     * @param int $groupId
     * @param null $parentId
     * @return mixed
     */
    public function getGroupItems($groupId, $parentId = null)
    {
        $data = app(FieldItemInterface::class)->getModel()
            ->where([
                'field_group_id' => $groupId,
                'parent_id' => $parentId
            ])
            ->orderBy('order', 'ASC')
            ->get();
        $this->resetModel();
        return $data;
    }

    /**
     * @param $groupId
     * @param null|int $parentId
     * @param bool $withValue
     * @param null|string $screen
     * @param null|int $object_id
     * @return array
     */
    public function getFieldGroupItems($groupId, $parentId = null, $withValue = false, $screen = null, $object_id = null)
    {
        $result = [];

        $fieldItems = $this->getGroupItems($groupId, $parentId);

        foreach ($fieldItems as $row) {
            $item = [
                'id' => $row->id,
                'title' => $row->title,
                'slug' => $row->slug,
                'instructions' => $row->instructions,
                'type' => $row->type,
                'options' => json_decode($row->options),
                'items' => $this->getFieldGroupItems($groupId, $row->id, $withValue, $screen, $object_id),
            ];
            if ($withValue === true) {
                if ($row->type === 'repeater') {
                    $item['value'] = $this->getRepeaterValue($item['items'], $this->getFieldItemValue($row, $screen, $object_id));
                } else {
                    $item['value'] = $this->getFieldItemValue($row, $screen, $object_id);
                }

                if ($row->type == 'file') {
                    $item['value'] = app(MediaFileInterface::class)->getFirstBy(['id' => $item['value']]);
                }
            }

            $result[] = $item;
        }

        return $result;
    }

    /**
     * @param $fieldItem
     * @param $screen
     * @param $object_id
     * @return null
     */
    protected function getFieldItemValue($fieldItem, $screen, $object_id)
    {

        $field = app(CustomFieldInterface::class)->getFirstBy([
            'use_for' => $screen,
            'use_for_id' => $object_id,
            'field_item_id' => $fieldItem->id,
        ]);

        return ($field) ? $field->value : null;
    }

    /**
     * @param $items
     * @param $data
     * @return array|null
     */
    protected function getRepeaterValue($items, $data)
    {
        if (!$items) {
            return null;
        }
        $data = ($data) ?: [];
        if (!is_array($data)) {
            $data = json_decode($data, true);
        }

        $result = [];
        foreach ($data as $key => $row) {
            $cloned = $items;
            foreach ($cloned as $keyItem => $item) {
                foreach ($row as $currentData) {
                    if ((int)$item['id'] === (int)$currentData['field_item_id']) {
                        if ($item['type'] === 'repeater') {
                            $item['value'] = $this->getRepeaterValue($item['items'], $currentData['value']);
                        } else {
                            $item['value'] = $currentData['value'];
                        }
                        $cloned[$keyItem] = $item;
                    }
                }
            }
            $result[$key] = $cloned;
        }
        return $result;
    }
}
