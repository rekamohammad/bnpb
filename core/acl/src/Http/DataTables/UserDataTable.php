<?php

namespace Botble\ACL\Http\DataTables;

use Botble\ACL\Repositories\Interfaces\UserInterface;
use Botble\Base\Http\DataTables\DataTableAbstract;

class UserDataTable extends DataTableAbstract
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
            })
            ->editColumn('username', function ($item) {
                return anchor_link(route('user.profile.view', $item->id), $item->username);
            })
            ->editColumn('created_at', function ($item) {
                return date_from_database($item->created_at, config('cms.date_format.date'));
            })
            ->editColumn('role_name', function ($item) {
                return view('acl::users.partials.role', compact('item'))->render();
            })
            ->editColumn('status', function ($item) {
                return table_status(acl_is_user_activated($item) ? 1 : 0);
            })
            ->removeColumn('role_id');
        return apply_filters(BASE_FILTER_GET_LIST_DATA, $data, USER_MODULE_SCREEN_NAME)
            ->addColumn('operations', function ($item) {
                return view('acl::users.partials.actions', compact('item'))->render();
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
        $query = $model->leftJoin('role_users', 'users.id', '=', 'role_users.user_id')
            ->leftJoin('roles', 'roles.id', '=', 'role_users.role_id')
            ->select([
                'users.id',
                'users.username',
                'users.email',
                'roles.name as role_name',
                'roles.id as role_id',
                'users.updated_at',
                'users.created_at',
            ]);
        return $this->applyScopes(apply_filters(BASE_FILTER_DATATABLES_QUERY, $query, $model, USER_MODULE_SCREEN_NAME));
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
                'title' => trans('acl::users.username'),
                'class' => 'text-left searchable',
            ],
            'email' => [
                'name' => 'users.email',
                'title' => trans('acl::users.email'),
                'class' => 'searchable',
            ],
            'role_name' => [
                'name' => 'role_name',
                'title' => trans('acl::users.role'),
            ],
            'created_at' => [
                'name' => 'users.created_at',
                'title' => trans('bases::tables.created_at'),
                'width' => '100px',
                'class' => 'searchable',
            ],
            'status' => [
                'name' => 'users.status',
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
                'link' => route('users.create'),
                'text' => view('bases::elements.tables.actions.create')->render(),
            ],
            'invite' => [
                'link' => '#invite_modal',
                'text' => view('acl::users.partials.actions.invite')->render(),
            ],
        ];
        return apply_filters(BASE_FILTER_DATATABLES_BUTTONS, $buttons, USER_MODULE_SCREEN_NAME);
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
                'link' => route('users.delete.many'),
                'text' => view('bases::elements.tables.actions.delete')->render(),
            ],
            'activate' => [
                'link' => route('users.change.status', ['status' => 1]),
                'text' => view('bases::elements.tables.actions.activate')->render(),
            ],
            'deactivate' => [
                'link' => route('users.change.status', ['status' => 0]),
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
        return USER_MODULE_SCREEN_NAME;
    }
}
