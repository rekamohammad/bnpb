<?php

namespace Botble\Gallery\Http\DataTables;

use Botble\Base\Http\DataTables\DataTableAbstract;
use Botble\Gallery\Repositories\Interfaces\GalleryInterface;

class GalleryDataTable extends DataTableAbstract
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
                return anchor_link(route('galleries.edit', $item->id), $item->name);
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

        return apply_filters(BASE_FILTER_GET_LIST_DATA, $data, GALLERY_MODULE_SCREEN_NAME)
            ->addColumn('operations', function ($item) {
                return table_actions('galleries.edit', 'galleries.delete', $item);
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
        $model = app(GalleryInterface::class)->getModel();
        /**
         * @var \Eloquent $model
         */
        $query = $model->select(['galleries.id', 'galleries.name', 'galleries.order', 'galleries.created_at', 'galleries.status']);
        return $this->applyScopes(apply_filters(BASE_FILTER_DATATABLES_QUERY, $query, $model, GALLERY_MODULE_SCREEN_NAME));
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
                'name' => 'galleries.id',
                'title' => trans('bases::tables.id'),
                'width' => '20px',
                'class' => 'searchable searchable_id',
            ],
            'name' => [
                'name' => 'galleries.name',
                'title' => trans('bases::tables.name'),
                'class' => 'text-left searchable',
            ],
            'order' => [
                'name' => 'galleries.order',
                'title' => trans('bases::tables.order'),
                'width' => '100px',
            ],
            'created_at' => [
                'name' => 'galleries.created_at',
                'title' => trans('bases::tables.created_at'),
                'width' => '100px',
                'class' => 'searchable',
            ],
            'status' => [
                'name' => 'galleries.status',
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
                'link' => route('galleries.create'),
                'text' => view('bases::elements.tables.actions.create')->render(),
            ],
        ];
        return apply_filters(BASE_FILTER_DATATABLES_BUTTONS, $buttons, GALLERY_MODULE_SCREEN_NAME);
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
                'link' => route('galleries.delete.many'),
                'text' => view('bases::elements.tables.actions.delete')->render(),
            ],
            'activate' => [
                'link' => route('galleries.change.status', ['status' => 1]),
                'text' => view('bases::elements.tables.actions.activate')->render(),
            ],
            'deactivate' => [
                'link' => route('galleries.change.status', ['status' => 0]),
                'text' => view('bases::elements.tables.actions.deactivate')->render(),
            ],
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
        return GALLERY_MODULE_SCREEN_NAME;
    }
}
