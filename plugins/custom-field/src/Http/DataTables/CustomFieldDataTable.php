<?php

namespace Botble\CustomField\Http\DataTables;

use Botble\Base\Http\DataTables\DataTableAbstract;
use Botble\CustomField\Repositories\Interfaces\FieldGroupInterface;

class CustomFieldDataTable extends DataTableAbstract
{
    /**
     * Display ajax response.
     *
     * @return \Illuminate\Http\JsonResponse
     * @author Sang Nguyen
     * @since 2.1
     */
    public function ajax()
    {
        $data = $this->datatables
            ->eloquent($this->query())
            ->editColumn('title', function ($item) {
                return anchor_link(route('custom-fields.edit', $item->id), $item->title);
            })
            ->editColumn('checkbox', function ($item) {
                return table_checkbox($item->id);
            })
            ->editColumn('created_at', function ($item) {
                return date_from_database($item->created_at, config('cms.date_format.date'));
            })
            ->editColumn('status', function ($item) {
                return table_status($item->status);
            });

        return apply_filters(BASE_FILTER_GET_LIST_DATA, $data, CUSTOM_FIELD_MODULE_SCREEN_NAME)
            ->addColumn('operations', function ($item) {
                return table_actions('custom-fields.edit', 'custom-fields.delete', $item);
            })
            ->escapeColumns([])
            ->make(true);
    }

    /**
     * Get the query object to be processed by datatables.
     *
     * @return \Illuminate\Database\Query\Builder|\Illuminate\Database\Eloquent\Builder
     * @author Sang Nguyen
     * @since 2.1
     */
    public function query()
    {
        $model = app(FieldGroupInterface::class)->getModel();
        /**
         * @var \Eloquent $model
         */
        $query = $model->select(['field_groups.id', 'field_groups.title', 'field_groups.status',  'field_groups.order',]);
        return $this->applyScopes(apply_filters(BASE_FILTER_DATATABLES_QUERY, $query, $model, CUSTOM_FIELD_MODULE_SCREEN_NAME));
    }

    /**
     * @return array
     * @author Sang Nguyen
     * @since 2.1
     */
    public function columns()
    {
        return [
            'id' => [
                'name' => 'field_groups.id',
                'title' => trans('bases::tables.id'),
                'width' => '20px',
                'class' => 'searchable searchable_id',
            ],
            'title' => [
                'name' => 'field_groups.title',
                'title' => trans('bases::tables.name'),
                'class' => 'text-left searchable',
            ],
            'created_at' => [
                'name' => 'field_groups.created_at',
                'title' => trans('bases::tables.created_at'),
                'width' => '100px',
                'class' => 'searchable',
            ],
            'status' => [
                'name' => 'field_groups.status',
                'title' => trans('bases::tables.status'),
                'width' => '100px',
            ],
        ];
    }

    /**
     * @return array
     * @author Sang Nguyen
     * @since 2.1
     */
    public function buttons()
    {
        $buttons = [
            'create' => [
                'link' => route('custom-fields.create'),
                'text' => view('bases::elements.tables.actions.create')->render(),
            ],
        ];
        return apply_filters(BASE_FILTER_DATATABLES_BUTTONS, $buttons, CUSTOM_FIELD_MODULE_SCREEN_NAME);
    }

    /**
     * @return array
     * @author Sang Nguyen
     * @since 2.1
     */
    public function actions()
    {
        return [
            'delete' => [
                'link' => route('custom-fields.delete.many'),
                'text' => view('bases::elements.tables.actions.delete')->render(),
            ],
            'activate' => [
                'link' => route('custom-fields.change.status', ['status' => 1]),
                'text' => view('bases::elements.tables.actions.activate')->render(),
            ],
            'deactivate' => [
                'link' => route('custom-fields.change.status', ['status' => 0]),
                'text' => view('bases::elements.tables.actions.deactivate')->render(),
            ]
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     * @author Sang Nguyen
     * @since 2.1
     */
    protected function filename()
    {
        return CUSTOM_FIELD_MODULE_SCREEN_NAME;
    }
}
