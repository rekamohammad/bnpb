<?php

namespace Botble\ACL\Http\Controllers;

use Botble\ACL\Http\DataTables\UserDataTable;
use Botble\ACL\Http\Requests\CreateUserRequest;
use Botble\ACL\Http\Requests\UpdatePasswordRequest;
use Botble\ACL\Http\Requests\UpdateProfileRequest;
use Botble\ACL\Http\Requests\ChangeProfileImageRequest;
use Botble\ACL\Http\Requests\InviteRequest;
use Assets;
use Botble\ACL\Models\User;
use Botble\ACL\Models\UserMeta;
use Botble\ACL\Repositories\Interfaces\InviteInterface;
use Botble\ACL\Repositories\Interfaces\RoleInterface;
use Botble\ACL\Repositories\Interfaces\RoleUserInterface;
use Botble\ACL\Repositories\Interfaces\UserInterface;
use Botble\ACL\Services\ChangePasswordService;
use Botble\ACL\Services\CreateUserService;
use Botble\ACL\Services\UpdateProfileImageService;
use Botble\Base\Http\Controllers\BaseController;
use EmailHandler;
use Illuminate\Http\Request;
use Exception;

class UserController extends BaseController
{

    /**
     * @var UserInterface
     */
    protected $userRepository;

    /**
     * @var RoleUserInterface
     */
    protected $roleUserRepository;

    /**
     * @var RoleInterface
     */
    protected $roleRepository;

    /**
     * @var InviteInterface
     */
    protected $inviteRepository;

    /**
     * UserController constructor.
     * @param UserInterface $userRepository
     * @param RoleUserInterface $roleUserRepository
     * @param RoleInterface $roleRepository
     * @param InviteInterface $inviteRepository
     */
    public function __construct(
        UserInterface $userRepository,
        RoleUserInterface $roleUserRepository,
        RoleInterface $roleRepository,
        InviteInterface $inviteRepository
    ) {
        $this->userRepository = $userRepository;
        $this->roleUserRepository = $roleUserRepository;
        $this->roleRepository = $roleRepository;
        $this->inviteRepository = $inviteRepository;
    }

    /**
     * Display all users
     * @param UserDataTable $dataTable
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @author Sang Nguyen
     */
    public function getList(UserDataTable $dataTable)
    {
        page_title()->setTitle(trans('acl::users.list'));

        Assets::addJavascript(['datatables', 'bootstrap-editable']);
        Assets::addStylesheets(['datatables', 'bootstrap-editable']);
        Assets::addAppModule(['datatables']);

        $roles = $this->roleRepository->pluck('name', 'id');

        return $dataTable->render('acl::users.list', compact('roles'));
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @author Sang Nguyen
     */
    public function getCreate()
    {
        page_title()->setTitle('Create new user');

        $roles = $this->roleRepository->pluck('name', 'id');
        return view('acl::users.create', compact('roles'));
    }

    /**
     * @param CreateUserRequest $request
     * @param CreateUserService $service
     * @return \Illuminate\Http\RedirectResponse
     * @author Sang Nguyen
     */
    public function postCreate(CreateUserRequest $request, CreateUserService $service)
    {

        $user = $service->execute($request);

        do_action(BASE_ACTION_AFTER_CREATE_CONTENT, USER_MODULE_SCREEN_NAME, $request, $user);

        if ($request->input('submit') === 'save') {
            return redirect()->route('users.list')->with('success_msg', trans('bases::notices.create_success_message'));
        }
        return redirect()->route('user.profile.view', $user->id)->with('success_msg', trans('bases::notices.create_success_message'));
    }

    /**
     * @param $id
     * @param Request $request
     * @return array|\Illuminate\Http\JsonResponse
     * @author Sang Nguyen
     */
    public function getDelete($id, Request $request)
    {
        if (acl_get_current_user_id() == $id) {
            return [
                'error' => true,
                'message' => trans('acl::users.delete_user_logged_in'),
            ];
        }

        try {
            $user = $this->userRepository->findById($id);
            $this->userRepository->delete($user);
            do_action(BASE_ACTION_AFTER_DELETE_CONTENT, USER_MODULE_SCREEN_NAME, $request, $user);
            return [
                'error' => false,
                'message' => trans('acl::users.deleted'),
            ];
        } catch (Exception $e) {
            return [
                'error' => true,
                'message' => trans('acl::users.cannot_delete'),
            ];
        }
    }

    /**
     * @param Request $request
     * @return array|\Illuminate\Http\JsonResponse
     * @author Sang Nguyen
     */
    public function postDeleteMany(Request $request)
    {
        $ids = $request->input('ids');
        if (empty($ids)) {
            return [
                'error' => true,
                'message' => trans('acl::users.no_select'),
            ];
        }

        foreach ($ids as $id) {
            if (acl_get_current_user_id() == $id) {
                return [
                    'error' => true,
                    'message' => trans('acl::users.delete_user_logged_in'),
                ];
            }
            try {
                $user = $this->userRepository->findById($id);
                $this->userRepository->delete($user);
                do_action(BASE_ACTION_AFTER_DELETE_CONTENT, USER_MODULE_SCREEN_NAME, $request, $user);
            } catch (Exception $ex) {
                return [
                    'error' => true,
                    'message' => trans('acl::users.cannot_delete'),
                ];
            }
        }
        return [
            'error' => false,
            'message' => trans('acl::users.deleted'),
        ];
    }

    /**
     * @param Request $request
     * @return array
     * @author Sang Nguyen
     */
    public function postChangeStatus(Request $request)
    {
        $ids = $request->input('ids');
        if (empty($ids)) {
            return [
                'error' => true,
                'message' => trans('acl::users.no_select'),
            ];
        }

        foreach ($ids as $id) {
            if ($request->input('status') == 0) {
                if (acl_get_current_user_id() == $id) {
                    return [
                        'error' => true,
                        'message' => trans('acl::users.lock_user_logged_in'),
                    ];
                }
            }
            $user = $this->userRepository->findById($id);

            if ($request->input('status', 0)) {
                if (acl_activate_user($user)) {
                    do_action(BASE_ACTION_AFTER_UPDATE_CONTENT, USER_MODULE_SCREEN_NAME, $request, $user);
                }
            } else {
                acl_deactivate_user($user);
            }

        }
        return [
            'error' => false,
            'status' => $request->input('status'),
            'message' => trans('acl::users.update_success'),
        ];
    }

    /**
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View| \Illuminate\Http\RedirectResponse
     * @author Sang Nguyen
     */
    public function getUserProfile($id)
    {
        page_title()->setTitle('User profile # ' . $id);

        Assets::addJavascript(['cropper', 'bootstrap-pwstrength']);
        Assets::addAppModule(['profile']);

        try {
            $user = $this->userRepository->findById($id);
        } catch (Exception $e) {
            return redirect()->back()
                ->with('error_msg', trans('acl::users.not_found'));
        }

        return view('acl::users.profile.base')
            ->with('user', $user);
    }

    /**
     * @param $id
     * @param UpdateProfileRequest $request
     * @return \Illuminate\Http\RedirectResponse
     * @author Sang Nguyen
     */
    public function postUpdateProfile($id, UpdateProfileRequest $request)
    {
        $user = $this->userRepository->findById($id);

        /**
         * @var User $currentUser
         */
        $currentUser = acl_get_current_user();
        if (($currentUser->hasPermission('users.update-profile') && $currentUser->getUserId() === $user->id) || $currentUser->isSuperUser()) {
            if ($user->email !== $request->input('email')) {
                $users = $this->userRepository->count(['email' => $request->input('email')]);
                if (!$users) {
                    $user->email = $request->input('email');
                } else {
                    return redirect()->route('user.profile.view', [$id])
                        ->with('error_msg', trans('acl::users.email.exist'))
                        ->withInput();
                }
            }

            if ($user->username !== $request->input('username')) {
                $users = $this->userRepository->count(['username' => $request->input('username')]);
                if (!$users) {
                    $user->username = $request->input('username');
                } else {
                    return redirect()->route('user.profile.view', [$id])
                        ->with('error_msg', trans('acl::users.username_exist'))
                        ->withInput();
                }
            }
        }

        $user->fill($request->input());
        $user->completed_profile = 1;
        $this->userRepository->createOrUpdate($user);
        do_action(USER_ACTION_AFTER_UPDATE_PROFILE, USER_MODULE_SCREEN_NAME, $request, $user);

        return redirect()->route('user.profile.view', [$id])
            ->with('success_msg', trans('acl::users.update_profile_success'));
    }

    /**
     * @param $id
     * @param UpdatePasswordRequest $request
     * @param ChangePasswordService $service
     * @return \Illuminate\Http\RedirectResponse
     * @author Sang Nguyen
     */
    public function postChangePassword($id, UpdatePasswordRequest $request, ChangePasswordService $service)
    {
        $result = $service->execute($request);

        if ($result instanceof Exception) {
            return redirect()->back()
                ->with('error_msg', $result->getMessage());
        }

        return redirect()->route('user.profile.view', [$id])
            ->with('success_msg', trans('acl::users.password_update_success'));
    }

    /**
     * @param ChangeProfileImageRequest $request
     * @param UpdateProfileImageService $service
     * @return array
     * @author Sang Nguyen
     */
    public function postModifyProfileImage(ChangeProfileImageRequest $request, UpdateProfileImageService $service)
    {
        try {

            $result = $service->execute($request);

            if ($result instanceof  Exception) {
                return [
                    'error' => false,
                    'message' => $result->getMessage(),
                ];
            }

            return [
                'error' => false,
                'message' => trans('acl::users.update_avatar_success'),
                'result' => $result,
            ];

        } catch (Exception $ex) {
            return  [
                'error' => true,
                'message' => $ex->getMessage(),
            ];
        }
    }

    /**
     * Posts an invite to a user.
     *
     * @param InviteRequest $request
     * @return \Illuminate\Http\RedirectResponse
     * @author Sang Nguyen
     */
    public function postInviteUser(InviteRequest $request)
    {

        $user = $this->userRepository->getFirstBy(['email' => $request->input('email')]);

        $token = str_random(32);

        if (!$user) {

            /**
             * @var User $user
             */
            $user = $this->userRepository->createOrUpdate([
                'email' => $request->input('email'),
                'first_name' => $request->input('first_name'),
                'last_name' => $request->input('last_name'),
                'profile_image' => config('acl.avatar.default'),
                'username' => $this->userRepository->getUniqueUsernameFromEmail($request->input('email')),
            ]);

            $this->sentEmailInvite([
                'user' => $user->username,
                'token' => $token,
                'email' => $user->email,
                'content' => $request->input('message'),
                'name' => $user->getFullName(),
            ], [
                'name' => $user->getFullName(),
                'to' => $user->email,
            ]);

            $this->inviteRepository->createOrUpdate([
                'token' => $token,
                'user_id' => acl_get_current_user_id(),
                'invitee_id' => $user->id,
                'role_id' => $request->input('role'),
            ]);

            return redirect()->route('users.list')
                ->with('success_msg', trans('acl::users.invite_success'));
        } else {
            $existingInvite = $this->inviteRepository->getFirstBy(['invitee_id' => $user->id, 'accepted' => 0]);

            if (!$existingInvite) {
                $this->inviteRepository->createOrUpdate([
                    'token' => $token,
                    'user_id' => acl_get_current_user_id(),
                    'invitee_id' => $user->id,
                    'role_id' => $request->input('role'),
                ]);

            } else {
                $token = $existingInvite->token;
            }

            $this->sentEmailInvite([
                'user' => $user->username,
                'token' => $token,
                'email' => $user->email,
                'content' => $request->input('message'),
                'name' => $user->getFullName(),
            ], [
                'name' => $user->getFullName(),
                'to' => $user->email,
            ]);

            return redirect()->route('users.list')
                ->with('success_msg', trans('acl::users.invite_exist'));
        }
    }

    /**
     * @param $data
     * @param array $args
     * @return void
     * @author Sang Nguyen
     */
    protected function sentEmailInvite($data, $args = [])
    {
        EmailHandler::send(view('acl::emails.invite', compact('data'))->render(), trans('acl::auth.email.invite.title'), $args);
    }

    /**
     * @param string $lang
     * @return \Illuminate\Http\RedirectResponse
     * @author Sang Nguyen
     */
    public function getLanguage($lang)
    {
        if ($lang != false && array_key_exists($lang, Assets::getAdminLocales())) {
            if (acl_check_login()) {
                UserMeta::setMeta('admin-locale', $lang);
            }
            session()->put('admin-locale', $lang);
        }

        cache()->forget(md5('cache-dashboard-menu'));

        return redirect()->back();
    }

    /**
     * @param $theme
     * @return \Illuminate\Http\RedirectResponse
     * @author Sang Nguyen
     */
    public function getTheme($theme)
    {
        if (acl_check_login()) {
            UserMeta::setMeta('admin-theme', $theme);
        } else {
            session()->put('admin-theme', $theme);
        }

        try {
            return redirect()->back();
        } catch (Exception $e) {
            return redirect()->route('access.login');
        }
    }
}
