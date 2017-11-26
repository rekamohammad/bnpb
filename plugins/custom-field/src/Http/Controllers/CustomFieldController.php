<?php

namespace Botble\CustomField\Http\Controllers;

use Assets;
use Botble\Base\Http\Controllers\BaseController;
use Botble\CustomField\Http\DataTables\CustomFieldDataTable;
use Botble\CustomField\Http\Requests\CreateFieldGroupRequest;
use Botble\CustomField\Http\Requests\UpdateFieldGroupRequest;
use Botble\CustomField\Models\FieldItem;
use Botble\CustomField\Repositories\Interfaces\FieldItemInterface;
use Botble\CustomField\Repositories\Interfaces\FieldGroupInterface;
use Exception;
use Illuminate\Http\Request;

class CustomFieldController extends BaseController
{

    /**
     * @var FieldGroupInterface
     */
    protected $fieldGroupRepository;

    /**
     * @var FieldItemInterface
     */
    protected $fieldItemRepository;

    /**
     * @param FieldGroupInterface $fieldGroupRepository
     * @param FieldItemInterface $fieldItemRepository
     * @author Sang Nguyen
     */
    public function __construct(FieldGroupInterface $fieldGroupRepository, FieldItemInterface $fieldItemRepository)
    {
        $this->fieldGroupRepository = $fieldGroupRepository;
        $this->fieldItemRepository = $fieldItemRepository;
    }

    /**
     * @param CustomFieldDataTable $dataTable
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @author Sang Nguyen
     */
    public function getList(CustomFieldDataTable $dataTable)
    {
        page_title()->setTitle(trans('custom-field::custom-field.custom_field_name'));

        return $dataTable->renderTable(['title' => trans('custom-field::custom-field.custom_field_name')]);
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @author Sang Nguyen
     */
    public function getCreate()
    {
        page_title()->setTitle(trans('custom-field::custom-field.create_field_group'));

        Assets::addStylesheetsDirectly(['vendor/core/plugins/custom-field/css/edit-field-group.css']);
        Assets::addJavascriptDirectly('vendor/core/plugins/custom-field/js/edit-field-group.js');
        Assets::addJavascript(['jquery-ui']);

        $currentId = 0;

        $customFieldItems = json_encode([]);

        $object = $this->fieldGroupRepository->getModel();
        $oldInputs = old();
        if ($oldInputs) {
            foreach ($oldInputs as $key => $row) {
                if($key === 'customFieldItems') {
                    $customFieldItems = $row;
                } else {
                    $object->$key = $row;
                }
            }
        }
        
        return view('custom-field::create', compact('customFieldItems', 'currentId', 'object'));
    }

    /**
     * @param CreateFieldGroupRequest $request
     * @return \Illuminate\Http\RedirectResponse
     * @author Sang Nguyen
     */
    public function postCreate(CreateFieldGroupRequest $request)
    {
        $field_group = $this->fieldGroupRepository->getModel();
        $field_group->fill($request->input());
        $field_group->created_by = acl_get_current_user_id();
        $field_group->updated_by = acl_get_current_user_id();
        $this->fieldGroupRepository->createOrUpdate($field_group);

        if (!empty($request->input('group_items'))) {
            $this->editGroupItems(json_decode($request->input('group_items', []), true), $field_group->id);
        }

        if ($request->input('submit') === 'save') {
            return redirect()->route('custom-fields.list')->with('success_msg', trans('bases::notices.create_success_message'));
        } else {
            return redirect()->route('custom-fields.edit', $field_group->id)->with('success_msg', trans('bases::notices.create_success_message'));
        }
    }

    /**
     * @param array $items
     * @param int $groupId
     * @param int|null $parentId
     */
    protected function editGroupItems($items, $groupId, $parentId = null)
    {
        $position = 0;
        $items = (array)$items;
        foreach ($items as $row) {
            $position++;

            $id = (int)$row['id'];

            $field = $this->fieldItemRepository->getFirstBy(['id' => $id]);

            if (empty($field)) {
                $field = new FieldItem();
            }

            $field->field_group_id = $groupId;
            $field->parent_id = $parentId;
            $field->title = $row['title'];
            $field->order = $position;
            $field->type = $row['type'];
            $field->options = json_encode($row['options']);
            $field->instructions = $row['instructions'];

            $slug = str_slug($row['slug'], '_') ?: str_slug($row['title'], '_');

            $field->slug = $this->fieldItemRepository->makeUniqueSlug($id, $field->field_group_id, $field->parent_id, $slug);

            $this->fieldItemRepository->createOrUpdate($field);

            $this->editGroupItems($row['items'], $groupId, (int)$field->id);
        }
    }

    /**
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @author Sang Nguyen, Tedozi Manson
     */
    public function getEdit($id)
    {

        page_title()->setTitle(trans('custom-field::custom-field.edit_field_group') . ' #' . $id);

        Assets::addStylesheetsDirectly(['vendor/core/plugins/custom-field/css/edit-field-group.css']);
        Assets::addJavascriptDirectly('vendor/core/plugins/custom-field/js/edit-field-group.js');
        Assets::addJavascript(['jquery-ui']);

        $object = $this->fieldGroupRepository->findById($id);

        if (!$object) {
            return redirect()->route('custom-fields.edit')->with('error_msg', 'This field group not exists');
        }

        $customFieldItems = json_encode($this->fieldGroupRepository->getFieldGroupItems($id));

        return view('custom-field::edit', compact('object', 'customFieldItems'));
    }

    /**
     * @param $id
     * @param UpdateFieldGroupRequest $request
     * @return \Illuminate\Http\RedirectResponse
     * @author Sang Nguyen, Tedozi Manson
     */
    public function postEdit($id, UpdateFieldGroupRequest $request)
    {
        $field_group = $this->fieldGroupRepository->findById($id);
        $field_group->fill($request->input());
        $field_group->updated_by = acl_get_current_user_id();
        $this->fieldGroupRepository->createOrUpdate($field_group);

        if (!empty($request->input('deleted_items'))) {
            foreach (json_decode($request->input('deleted_items')) as $item) {
                $this->fieldItemRepository->deleteBy(['id' => $item]);
            }
        }

        if (!empty($request->input('group_items'))) {
            $this->editGroupItems(json_decode($request->input('group_items', []), true), $field_group->id);
        }

        if ($request->input('submit') === 'save') {
            return redirect()->route('custom-fields.list')->with('success_msg', trans('bases::notices.update_success_message'));
        } else {
            return redirect()->route('custom-fields.edit', $id)->with('success_msg', trans('bases::notices.update_success_message'));
        }
    }

    /**
     * @param $id
     * @param Request $request
     * @return array
     * @author Sang Nguyen
     */
    public function getDelete($id, Request $request)
    {
        try {
            $field_group = $this->fieldGroupRepository->findById($id);
            $this->fieldGroupRepository->delete($field_group);
            do_action(BASE_ACTION_AFTER_DELETE_CONTENT, CUSTOM_FIELD_MODULE_SCREEN_NAME, $request, $field_group);
            return [
                'error' => false,
                'message' => trans('custom-field::field-groups.deleted'),
            ];
        } catch (Exception $ex) {
            return [
                'error' => true,
                'message' => trans('custom-field::field-groups.cannot_delete'),
            ];
        }
    }

    /**
     * @param Request $request
     * @return array|\Illuminate\Http\JsonResponse
     * @author Sang Nguyen
     */
    public function postDeleteMany(Request $request)
    {
        $ids = $request->input('ids');
        if (empty($ids)) {
            return [
                'error' => true,
                'message' => trans('custom-field::field-groups.notices.no_select'),
            ];
        }

        foreach ($ids as $id) {
            $field_group = $this->fieldGroupRepository->findById($id);
            $this->fieldGroupRepository->delete($field_group);
            do_action(BASE_ACTION_AFTER_DELETE_CONTENT, CUSTOM_FIELD_MODULE_SCREEN_NAME, $request, $field_group);
        }

        return [
            'error' => false,
            'message' => trans('custom-field::field-groups.field_group_deleted'),
        ];
    }

    /**
     * @param Request $request
     * @return array
     * @author Sang Nguyen
     */
    public function postChangeStatus(Request $request)
    {
        $ids = $request->input('ids');
        if (empty($ids)) {
            return [
                'error' => true,
                'message' => trans('custom-field::field-groups.notices.no_select'),
            ];
        }

        foreach ($ids as $id) {
            $field_group = $this->fieldGroupRepository->findById($id);
            $field_group->status = $request->input('status');
            $this->fieldGroupRepository->createOrUpdate($field_group);
            do_action(BASE_ACTION_AFTER_UPDATE_CONTENT, CUSTOM_FIELD_MODULE_SCREEN_NAME, $request, $field_group);
        }

        return [
            'error' => false,
            'status' => $request->input('status'),
            'message' => trans('custom-field::field-groups.notices.update_success_message'),
        ];
    }
}
