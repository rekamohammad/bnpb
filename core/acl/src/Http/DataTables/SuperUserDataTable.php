<?php

namespace Botble\ACL\Http\DataTables;

use Botble\ACL\Repositories\Interfaces\UserInterface;
use Botble\Base\Http\DataTables\DataTableAbstract;

class SuperUserDataTable extends DataTableAbstract
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
            ->editColumn('checkbox', function ($item) {
                return table_checkbox($item->id);
            });

        return apply_filters(BASE_FILTER_GET_LIST_DATA, $data, SUPER_USER_MODULE_SCREEN_NAME)
            ->addColumn('operations', function ($item) {
                return view('acl::users.partials.delete', compact('item'))->render();
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
        $model = app(UserInterface::class)->getModel();
        /**
         * @var \Eloquent $model
         */
        $query = $model->select(['users.id', 'users.username', 'users.email', 'users.last_login'])->where(['users.super_user' => 1]);
        return $this->applyScopes(apply_filters(BASE_FILTER_DATATABLES_QUERY, $query, $model, SUPER_USER_MODULE_SCREEN_NAME));
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
                'name' => 'users.id',
                'title' => trans('bases::tables.id'),
                'width' => '20px',
                'class' => 'searchable searchable_id',
            ],
            'username' => [
                'name' => 'users.username',
                'title' => trans('bases::tables.name'),
                'class' => 'searchable',
            ],
            'email' => [
                'name' => 'users.email',
                'title' => trans('bases::tables.email'),
                'class' => 'searchable',
            ],
            'last_login' => [
                'name' => 'users.last_login',
                'title' => trans('bases::tables.last_login'),
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
            'add-supper' => [
                'link' => '#',
                'text' => view('acl::users.partials.actions.add-super')->render(),
            ],
        ];
        return apply_filters(BASE_FILTER_DATATABLES_BUTTONS, $buttons, SUPER_USER_MODULE_SCREEN_NAME);
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
                'link' => route('users-supers.delete.many'),
                'text' => view('bases::elements.tables.actions.delete')->render(),
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
        return SUPER_USER_MODULE_SCREEN_NAME;
    }
}
