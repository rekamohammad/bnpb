<?php

namespace Botble\ACL\Http\Controllers;

use Botble\ACL\Http\Requests\AcceptInviteRequest;
use Botble\ACL\Repositories\Interfaces\InviteInterface;
use Botble\ACL\Repositories\Interfaces\RoleInterface;
use Botble\ACL\Repositories\Interfaces\RoleUserInterface;
use Assets;
use Botble\ACL\Http\Requests\ForgotRequest;
use Botble\ACL\Http\Requests\LoginRequest;
use Botble\ACL\Http\Requests\ResetRequest;
use Botble\ACL\Repositories\Interfaces\UserInterface;
use Botble\ACL\Services\AcceptInviteService;
use Botble\ACL\Services\CheckResetPasswordService;
use Botble\ACL\Services\ForgotPasswordService;
use Botble\ACL\Services\LoginService;
use Botble\ACL\Services\ResetPasswordService;
use Botble\Base\Http\Controllers\BaseController;
use Illuminate\Http\Request;
use Sentinel;
use Socialite;
use Exception;

class AuthController extends BaseController
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
     * Show login page
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @author Sang Nguyen
     */
    public function getLogin()
    {
        page_title()->setTitle(trans('acl::auth.login_title'));

        Assets::addJavascript(['jquery-validation']);
        Assets::addAppModule(['login']);
        return view('acl::auth.login');
    }

    /**
     * Show forgot password page
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @author Sang Nguyen
     */
    public function getForgotPassword()
    {
        page_title()->setTitle(trans('acl::auth.forgot_password.title'));

        Assets::addJavascript(['jquery-validation']);
        Assets::addAppModule(['login']);
        return view('acl::auth.forgot-password');
    }

    /**
     * @param LoginRequest $request
     * @param LoginService $service
     * @return \Illuminate\Http\RedirectResponse
     * @author Sang Nguyen
     */
    public function postLogin(LoginRequest $request, LoginService $service)
    {
        $result = $service->execute($request);

        if (!empty($result)) {
            if ($result instanceof Exception) {
                return redirect()->route('access.login')->with('error_msg', $result->getMessage())->withInput();
            }
            return redirect()->intended()->with('success_msg', trans('acl::auth.login.success'));
        }

        return redirect()->route('access.login')->with('error_msg', trans('acl::auth.login.fail'))->withInput();
    }

    /**
     * @param ForgotRequest $request
     * @param ForgotPasswordService $service
     * @return \Illuminate\Http\RedirectResponse
     * @author Sang Nguyen
     */
    public function postForgotPassword(ForgotRequest $request, ForgotPasswordService $service)
    {
        $result = $service->execute($request);
        if ($result instanceof Exception) {
            return redirect()->route('access.forgot-password')->with('error_msg', $result->getMessage());
        }
        return redirect()->route('access.forgot-password')->with('success_msg', trans('acl::auth.reset.send.success'));
    }

    /**
     * @param Request $request
     * @param CheckResetPasswordService $service
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     * @author Sang Nguyen
     */
    public function getResetPassword(Request $request, CheckResetPasswordService $service)
    {
        page_title()->setTitle(trans('acl::auth.reset.title'));

        $user = $service->execute($request);
        if ($user instanceof Exception) {
            return redirect()->route('access.login')->with('error_msg', $user->getMessage());
        }

        $token = $request->input('token');
        Assets::addJavascript(['jquery-validation']);
        Assets::addAppModule(['login']);
        return view('acl::auth.reset', compact('user', 'token'));
    }

    /**
     * @param ResetRequest $request
     * @param ResetPasswordService $service
     * @return \Illuminate\Http\RedirectResponse
     * @author Sang Nguyen
     */
    public function postResetPassword(ResetRequest $request, ResetPasswordService $service)
    {
        $result = $service->execute($request);

        if ($result instanceof Exception) {
            return redirect()->route('access.reset-password', [$request->input('user'), $request->input('token')])->with('error_msg', $result->getMessage());
        }

        return redirect()->route('dashboard.index')->with('success_msg', trans('acl::auth.reset.success'));
    }

    /**
     * Logout
     * @return \Illuminate\Http\RedirectResponse
     * @author Sang Nguyen
     */
    public function getLogout()
    {
        do_action(AUTH_ACTION_AFTER_LOGOUT_SYSTEM, AUTH_MODULE_SCREEN_NAME, request(), acl_get_current_user());
        Sentinel::logout();
        return redirect()->route('access.login')->with('success_msg', trans('acl::auth.login.logout_success'));
    }

    /**
     * Redirect the user to the {provider} authentication page.
     *
     * @param $provider
     * @return mixed
     * @author Sang Nguyen
     */
    public function redirectToProvider($provider)
    {
        return Socialite::driver($provider)->redirect();
    }

    /**
     * Obtain the user information from {provider}.
     * @param $provider
     * @param $request
     * @return \Illuminate\Http\RedirectResponse
     * @author Sang Nguyen
     */
    public function handleProviderCallback($provider, Request $request)
    {
        try {
            /**
             * @var \Laravel\Socialite\AbstractUser $oAuth
             */
            $oAuth = Socialite::driver($provider)->user();
        } catch (Exception $ex) {
            return redirect()->route('access.login')->with('error_msg', $ex->getMessage());
        }

        $user = $this->userRepository->getFirstBy(['email' => $oAuth->getEmail()]);

        if ($user) {
            Sentinel::loginAndRemember($user);
            do_action(AUTH_ACTION_AFTER_LOGIN_SYSTEM, AUTH_MODULE_SCREEN_NAME, $request, acl_get_current_user());
            return redirect()->route('dashboard.index')->with('success_msg', trans('acl::auth.login.success'));
        }
        return redirect()->route('access.login')->with('error_msg', trans('acl::auth.login.dont_have_account'));
    }

    /**
     * Function that fires when a user accepts an invite.
     *
     * @param string $token Generated token
     * @return \Illuminate\Http\RedirectResponse
     * @author Sang Nguyen
     */
    public function getAcceptInvite($token)
    {
        page_title()->setTitle(trans('acl::auth.accept_invite'));

        if (empty($token)) {
            return redirect()->route('dashboard.index')
                ->with('error_msg', trans('acl::users.invite_not_exist'));
        }

        $invite = $this->inviteRepository->getFirstBy([
            'token' => $token,
            'accepted' => false,
        ]);

        if (!empty($invite)) {
            return view('acl::auth.invite', compact('token'));
        }
        return view('acl::auth.invite', ['error_msg' => trans('acl::users.invite_not_exist')]);
    }

    /**
     * @param AcceptInviteRequest|Request $request
     * @param AcceptInviteService $service
     * @return \Illuminate\Http\RedirectResponse
     * @author Sang Nguyen
     */
    public function postAcceptInvite(AcceptInviteRequest $request, AcceptInviteService $service)
    {
        $result = $service->execute($request);

        if ($result instanceof Exception) {
            return redirect()->back()->with('error_msg', $result->getMessage());
        }
        return redirect()->route('dashboard.index')->with('success_msg', trans('acl::users.accept_invite_success'));
    }
}
