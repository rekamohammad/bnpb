<?php

namespace Botble\ACL\Http\Controllers;

use Botble\ACL\Http\Requests\ForgotRequest;
use Botble\ACL\Http\Requests\LoginRequest;
use Botble\ACL\Http\Requests\RegisterRequest;
use Botble\ACL\Http\Requests\ResetRequest;
use Botble\ACL\Services\ActivateUserService;
use Botble\ACL\Services\CheckResetPasswordService;
use Botble\ACL\Services\ForgotPasswordService;
use Botble\ACL\Services\LoginService;
use Botble\ACL\Services\RegisterService;
use Botble\ACL\Services\ResetPasswordService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Sentinel;
use Theme;

class PublicController extends Controller
{

    /**
     * PublicController constructor.
     */
    public function __construct()
    {
        Theme::asset()->add('b-auth-css', 'vendor/core/css/auth.css');
    }

    /**
     * @return \Response
     * @author Sang Nguyen
     */
    public function getLogin()
    {
        Theme::breadcrumb()->add(__('Login'), route('public.index'));
        return Theme::of('acl::auth.partials.login-form')->render();
    }

    /**
     * @param LoginRequest $request
     * @param LoginService $service
     * @return \Illuminate\Http\RedirectResponse
     */
    public function postLogin(LoginRequest $request, LoginService $service)
    {
        $result = $service->execute($request);

        if (!empty($result)) {
            if ($result instanceof Exception) {
                return redirect()->route('public.access.login')->with('error_msg', $result->getMessage())->withInput();
            }
            return redirect()->route('public.index')->with('success_msg', trans('acl::auth.login.success'));
        }

        return redirect()->route('public.access.login')->with('error_msg', trans('acl::auth.login.fail'))->withInput();
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
        return redirect()->route('public.access.login')->with('success_msg', trans('acl::auth.login.logout_success'));
    }

    /**
     * @return \Response
     * @author Sang Nguyen
     */
    public function getForgotPassword()
    {
        Theme::breadcrumb()->add(__('Forgot password'), route('public.index'));
        return Theme::of('acl::auth.partials.forgot-password-form')->render();
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
            return redirect()->route('public.access.forgot-password')->with('error_msg', $result->getMessage());
        }
        return redirect()->route('public.access.forgot-password')->with('success_msg', trans('acl::auth.reset.send.success'));
    }

    /**
     * @param Request $request
     * @param CheckResetPasswordService $service
     * @return \Illuminate\Http\RedirectResponse|\Response
     * @author Sang Nguyen
     */
    public function getResetPassword(Request $request, CheckResetPasswordService $service)
    {
        $user = $service->execute($request);
        if ($user instanceof Exception) {
            return redirect()->route('public.access.login')->with('error_msg', $user->getMessage());
        }

        $token = $request->input('token');
        return Theme::of('acl::auth.partials.reset-password-form', compact('user', 'token'))->render();
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
            return redirect()->route('public.access.reset-password', [$request->input('user'), $request->input('token')])->with('error_msg', $result->getMessage());
        }
        return redirect()->route('public.index')->with('success_msg', trans('acl::auth.reset.success'));
    }

    /**
     * @return \Response
     * @author Sang Nguyen
     */
    public function getRegister()
    {
        return Theme::of('acl::auth.partials.register-form')->render();
    }

    /**
     * @param RegisterRequest $request
     * @param RegisterService $service
     * @return \Illuminate\Http\RedirectResponse
     * @author Sang Nguyen
     */
    public function postRegister(RegisterRequest $request, RegisterService $service)
    {
        $result = $service->execute($request);
        if ($result instanceof Exception) {
            return redirect()->back()->with('error_msg', $result->getMessage());
        }

        return redirect()->back()->with('success_msg', __('Register successfully! The system send to you an email, please click to activation link in that email to active your account'));
    }

    /**
     * @param $code
     * @param $username
     * @param ActivateUserService $service
     * @return \Illuminate\Http\RedirectResponse
     * @author Sang Nguyen
     */
    public function getActivation($code, $username, ActivateUserService $service)
    {
        $result = $service->execute($code, $username);
        if ($result instanceof Exception) {
            return redirect()->back()->with('error_msg', $result->getMessage());
        }
        return redirect()->route('public.index')->with('success_msg', __('Activate account successfully!'));
    }
}