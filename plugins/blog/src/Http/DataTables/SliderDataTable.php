<?php

namespace Botble\Blog\Http\DataTables;

use Botble\Base\Http\DataTables\DataTableAbstract;
use Botble\Blog\Repositories\Interfaces\SliderInterface;

class SliderDataTable extends DataTableAbstract
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
                return anchor_link(route('slider.edit', $item->id), $item->name);
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
                return table_actions('slider.edit', 'slider.delete', $item);
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
        $model = app(SliderInterface::class)->getModel();
        /**
         * @var \Eloquent $model
         */
        $query = $model->orderBy('sliders.created_at', 'desc')
                ->select(['sliders.id', 'sliders.name', 'sliders.created_at', 'sliders.status']);
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
                'name' => 'sliders.id',
                'title' => trans('bases::tables.id'),
                'width' => '20px',
                'class' => 'searchable searchable_id',
            ],
            'name' => [
                'name' => 'sliders.name',
                'title' => trans('bases::tables.name'),
                'class' => 'text-left searchable',
                'filter' => [
                    'enable' => true,
                    'type' => 'text',
                    'placeholder' => 'Search',
                ],
            ],
            'created_at' => [
                'name' => 'sliders.created_at',
                'title' => trans('bases::tables.created_at'),
                'width' => '100px',
                'class' => 'searchable',
            ],
            'status' => [
                'name' => 'sliders.status',
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
              'link' => route('slider.create'),
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
                'link' => route('slider.delete.many'),
                'text' => view('bases::elements.tables.actions.delete')->render(),
            ],
            'activate' => [
                'link' => route('slider.change.status', ['status' => 1]),
                'text' => view('bases::elements.tables.actions.activate')->render(),
            ],
            'deactivate' => [
                'link' => route('slider.change.status', ['status' => 0]),
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
