<?php

namespace Botble\Page\Http\DataTables;

use Botble\Base\Http\DataTables\DataTableAbstract;
use Botble\Page\Repositories\Interfaces\PageInterface;

class PageDataTable extends DataTableAbstract
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
                return anchor_link(route('pages.edit', $item->id), $item->name);
            })
            ->editColumn('checkbox', function ($item) {
                return table_checkbox($item->id);
            })
            ->editColumn('template', function ($item) {
                return array_get(get_page_templates(), $item->template);
            })
            ->editColumn('created_at', function ($item) {
                return date_from_database($item->created_at, config('cms.date_format.date'));
            })
            ->editColumn('status', function ($item) {
                return table_status($item->status);
            });

        return apply_filters(BASE_FILTER_GET_LIST_DATA, $data, PAGE_MODULE_SCREEN_NAME)
            ->addColumn('operations', function ($item) {
                return table_actions('pages.edit', 'pages.delete', $item);
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
        $model = app(PageInterface::class)->getModel();
        /**
         * @var \Eloquent $model
         */
        $query = $model->select(['pages.id', 'pages.name', 'pages.template', 'pages.created_at', 'pages.status']);
        return $this->applyScopes(apply_filters(BASE_FILTER_DATATABLES_QUERY, $query, $model, PAGE_MODULE_SCREEN_NAME));
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
                'name' => 'pages.id',
                'title' => trans('bases::tables.id'),
                'width' => '20px',
                'class' => 'searchable searchable_id',
            ],
            'name' => [
                'name' => 'pages.name',
                'title' => trans('bases::tables.name'),
                'class' => 'text-left searchable',
            ],
            'template' => [
                'name' => 'pages.template',
                'title' => trans('bases::tables.template'),
                'class' => 'searchable',
            ],
            'created_at' => [
                'name' => 'pages.created_at',
                'title' => trans('bases::tables.created_at'),
                'width' => '100px',
                'class' => 'searchable',
            ],
            'status' => [
                'name' => 'pages.status',
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
              'link' => route('pages.create'),
              'text' => view('bases::elements.tables.actions.create')->render(),
          ],
        ];

        return apply_filters(BASE_FILTER_DATATABLES_BUTTONS, $buttons, PAGE_MODULE_SCREEN_NAME);
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
                'link' => route('pages.delete.many'),
                'text' => view('bases::elements.tables.actions.delete')->render(),
            ],
            'activate' => [
                'link' => route('pages.change.status', ['status' => 1]),
                'text' => view('bases::elements.tables.actions.activate')->render(),
            ],
            'deactivate' => [
                'link' => route('pages.change.status', ['status' => 0]),
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
        return PAGE_MODULE_SCREEN_NAME;
    }
}
