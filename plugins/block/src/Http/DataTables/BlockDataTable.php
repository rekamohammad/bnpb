<?php

namespace Botble\Block\Http\DataTables;

use Botble\Base\Http\DataTables\DataTableAbstract;
use Botble\Block\Repositories\Interfaces\BlockInterface;

class BlockDataTable extends DataTableAbstract
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
                return anchor_link(route('block.edit', $item->id), $item->name);
            })
            ->editColumn('alias', function($item) {
                return generate_shortcode('static-block', ['alias' => $item->alias]);
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

        return apply_filters(BASE_FILTER_GET_LIST_DATA, $data, BLOCK_MODULE_SCREEN_NAME)
            ->addColumn('operations', function ($item) {
                return table_actions('block.edit', 'block.delete', $item);
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
        $model = app(BlockInterface::class)->getModel();
        /**
         * @var \Eloquent $model
         */
        $query = $model->select(['blocks.id', 'blocks.alias', 'blocks.name', 'blocks.created_at', 'blocks.status']);
        return $this->applyScopes(apply_filters(BASE_FILTER_DATATABLES_QUERY, $query, $model, BLOCK_MODULE_SCREEN_NAME));
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
                'name' => 'blocks.id',
                'title' => trans('bases::tables.id'),
                'width' => '20px',
                'class' => 'searchable searchable_id',
            ],
            'name' => [
                'name' => 'blocks.name',
                'title' => trans('bases::tables.name'),
                'class' => 'text-left searchable',
            ],
            'alias' => [
                'name' => 'blocks.alias',
                'title' => trans('bases::tables.shortcode'),
                'class' => 'searchable',
            ],
            'created_at' => [
                'name' => 'blocks.created_at',
                'title' => trans('bases::tables.created_at'),
                'width' => '100px',
                'class' => 'searchable',
            ],
            'status' => [
                'name' => 'blocks.status',
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
                'link' => route('block.create'),
                'text' => view('bases::elements.tables.actions.create')->render(),
            ],
        ];
        return apply_filters(BASE_FILTER_DATATABLES_BUTTONS, $buttons, BLOCK_MODULE_SCREEN_NAME);
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
                'link' => route('block.delete.many'),
                'text' => view('bases::elements.tables.actions.delete')->render(),
            ],
            'activate' => [
                'link' => route('block.change.status', ['status' => 1]),
                'text' => view('bases::elements.tables.actions.activate')->render(),
            ],
            'deactivate' => [
                'link' => route('block.change.status', ['status' => 0]),
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
        return BLOCK_MODULE_SCREEN_NAME;
    }
}
