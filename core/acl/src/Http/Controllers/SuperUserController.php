<?php

namespace Botble\ACL\Http\Controllers;

use Botble\ACL\Http\DataTables\SuperUserDataTable;
use Botble\ACL\Repositories\Interfaces\UserInterface;
use Botble\Base\Http\Controllers\BaseController;
use Exception;
use Illuminate\Http\Request;

class SuperUserController extends BaseController
{
    /**
     * @var UserInterface
     */
    protected $userRepository;

    /**
     * SuperUserController constructor.
     * @param UserInterface $userRepository
     */
    public function __construct(UserInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }


    /**
     * @param SuperUserDataTable $dataTable
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @author Sang Nguyen
     */
    public function getList(SuperUserDataTable $dataTable)
    {
        page_title()->setTitle(trans('bases::system.user.list_super'));

        return $dataTable->render('acl::users.super-user');
    }

    /**
     * @param $id
     * @return array
     * @author Sang Nguyen
     */
    public function getDelete($id)
    {
        try {
            $user = $this->userRepository->findById($id);
        } catch (Exception $e) {
            return [
                'error' => true,
                'message' => trans('bases::system.cannot_find_user'),
            ];
        }
        $user->updatePermission('superuser', false);
        $user->updatePermission('manage_supers', false);
        $user->super_user = 0;
        $user->manage_supers = 0;
        $this->userRepository->createOrUpdate($user);
        return [
            'error' => false,
            'message' => trans('bases::system.supper_revoked'),
        ];
    }

    /**
     * @param Request $request
     * @return array
     * @author Sang Nguyen
     */
    public function postDeleteMany(Request $request)
    {
        $ids = $request->input('ids');
        if (empty($ids)) {
            return [
                'error' => true,
                'message' => trans('bases::system.no_select'),
            ];
        }

        foreach ($ids as $id) {
            try {
                $user = $this->userRepository->findById($id);
            } catch (Exception $e) {
                return [
                    'error' => true,
                    'message' => trans('bases::system.cannot_find_user'),
                ];
            }
            $user->updatePermission('superuser', false);
            $user->updatePermission('manage_supers', false);
            $user->super_user = 0;
            $user->manage_supers = 0;
            $this->userRepository->createOrUpdate($user);
        }
        return [
            'error' => false,
            'message' => trans('bases::system.supper_revoked'),
        ];
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     * @author Sang Nguyen
     */
    public function postCreate(Request $request)
    {
        try {
            $user = $this->userRepository->getFirstBy(['email' => $request->input('email')]);
            if (!empty($user)) {
                $user->updatePermission('superuser', true);
                $user->super_user = 1;
                $this->userRepository->createOrUpdate($user);
                return redirect()->route('users-supers.list')->with('success_msg', trans('bases::system.supper_granted'));
            }
            return redirect()->route('users-supers.list')->with('error_msg', trans('bases::system.cant_find_user_with_email'))->withInput();
        } catch (Exception $e) {
            return redirect()->route('users-supers.list')->with('error_msg', trans('bases::system.cant_find_user_with_email'))->withInput();
        }

    }
}
