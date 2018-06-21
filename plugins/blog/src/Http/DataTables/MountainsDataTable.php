<?php

namespace Botble\Blog\Http\DataTables;

use Botble\Base\Http\DataTables\DataTableAbstract;
use Botble\Blog\Repositories\Interfaces\MountainsInterface;

class MountainsDataTable extends DataTableAbstract
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
            ->editColumn('name', function ($item) {
                return anchor_link(route('mountains.edit', $item->id), $item->name);
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

        return apply_filters(BASE_FILTER_GET_LIST_DATA, $data, POST_MODULE_SCREEN_NAME)
            ->addColumn('operations', function ($item) {
                return table_actions('mountains.edit', 'mountains.delete', $item);
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
        $model = app(MountainsInterface::class)->getModel();

        /**
         * @var \Eloquent $model
         */
        $query = $model->select(['mountains.id', 'mountains.name', 'mountains.date_of_the_incident', 'mountains.notes', 'mountains.mountain_status', 'mountains.created_at', 'mountains.status']);
        
        return $this->applyScopes(apply_filters(BASE_FILTER_DATATABLES_QUERY, $query, $model, POST_MODULE_SCREEN_NAME));
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
                'name' => 'mountains.id',
                'title' => trans('bases::tables.id'),
                'width' => '20px',
                'class' => 'searchable searchable_id',
            ],
            'name' => [
                'name' => 'mountains.name',
                'title' => trans('bases::tables.name'),
                'class' => 'text-left searchable',
                'filter' => [
                    'enable' => true,
                    'type' => 'text',
                    'placeholder' => 'Search',
                ],
            ],
            'mountain_status' => [
                'name' => 'mountains.mountain_status',
                'title' => trans('bases::tables.mountain_status'),
                'class' => 'text-left searchable',
                'filter' => [
                    'enable' => true,
                    'type' => 'text',
                    'placeholder' => 'Search',
                ],
            ],
            'date_of_the_incident' => [
                'name' => 'mountains.date_of_the_incident',
                'title' => trans('bases::tables.date_of_the_incident'),
                'class' => 'text-left searchable',
                'filter' => [
                    'enable' => true,
                    'type' => 'text',
                    'placeholder' => 'Search',
                ],
            ],
            'created_at' => [
                'name' => 'mountains.created_at',
                'title' => trans('bases::tables.created_at'),
                'width' => '100px',
                'class' => 'searchable',
            ],
            'status' => [
                'name' => 'mountains.status',
                'title' => trans('bases::tables.status'),
                'width' => '100px',
                'class' => 'column-select-search',
                'filter' => [
                    'enable' => true,
                    'type' => 'select',
                    'data' => [
                        1 => 'Activated',
                        0 => 'Deactivated',
                    ],
                    'placeholder' => 'Type to filter',
                ],
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
              'link' => route('mountains.create'),
              'text' => view('bases::elements.tables.actions.create')->render(),
          ],
        ];

        return apply_filters(BASE_FILTER_DATATABLES_BUTTONS, $buttons, POST_MODULE_SCREEN_NAME);
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
                'link' => route('mountains.delete.many'),
                'text' => view('bases::elements.tables.actions.delete')->render(),
            ],
            'activate' => [
                'link' => route('mountains.change.status', ['status' => 1]),
                'text' => view('bases::elements.tables.actions.activate')->render(),
            ],
            'deactivate' => [
                'link' => route('mountains.change.status', ['status' => 0]),
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
        return POST_MODULE_SCREEN_NAME;
    }
}
