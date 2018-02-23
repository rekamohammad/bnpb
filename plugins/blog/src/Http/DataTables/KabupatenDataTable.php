<?php

namespace Botble\Blog\Http\DataTables;

use Botble\Base\Http\DataTables\DataTableAbstract;
use Botble\Blog\Repositories\Interfaces\KabupatenInterface;

class KabupatenDataTable extends DataTableAbstract
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
                return anchor_link(route('kabupaten.edit', $item->id), $item->name);
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
                return table_actions('kabupaten.edit', 'kabupaten.delete', $item);
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
        $model = app(kabupatenInterface::class)->getModel();

        /**
         * @var \Eloquent $model
         */

        $query = $model->select(['links.id', 'provinsi.name','links.kabupaten', 'links.created_at', 'links.status'])->join('provinsi','links.province','=','provinsi.id')->where('links.categories', '=', 'Kabupaten');

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
                'name' => 'links.id',
                'title' => trans('bases::tables.id'),
                'width' => '20px',
                'class' => 'searchable searchable_id',
            ],
            'kabupaten' => [
                'name' => 'links.name',
                'title' => trans('bases::tables.kabupaten'),
                'class' => 'text-left searchable',
                'filter' => [
                    'enable' => true,
                    'type' => 'text',
                    'placeholder' => 'Search',
                ],
            ],
			'name' => [
                'name' => 'links.kabupaten',
                'title' => trans('bases::tables.provinsi'),
                'width' => '100px',
                'class' => 'searchable',
            ],
            'created_at' => [
                'name' => 'links.created_at',
                'title' => trans('bases::tables.created_at'),
                'width' => '100px',
                'class' => 'searchable',
            ],
            'status' => [
                'name' => 'links.status',
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
              'link' => route('kabupaten.create'),
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
                'link' => route('kabupaten.delete.many'),
                'text' => view('bases::elements.tables.actions.delete')->render(),
            ],
            'activate' => [
                'link' => route('kabupaten.change.status', ['status' => 1]),
                'text' => view('bases::elements.tables.actions.activate')->render(),
            ],
            'deactivate' => [
                'link' => route('kabupaten.change.status', ['status' => 0]),
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
