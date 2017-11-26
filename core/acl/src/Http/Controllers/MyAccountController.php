<?php

namespace Botble\ACL\Http\Controllers;

use Botble\ACL\Http\Requests\ChangeProfileImageRequest;
use Botble\ACL\Http\Requests\EditAccountRequest;
use Botble\ACL\Http\Requests\UpdatePasswordRequest;
use Botble\ACL\Repositories\Interfaces\UserInterface;
use Botble\ACL\Services\ChangePasswordService;
use Exception;
use Illuminate\Routing\Controller;
use Theme;

class MyAccountController extends Controller
{
    /**
     * @var UserInterface
     */
    protected $userRepository;

    /**
     * PublicController constructor.
     * @param UserInterface $userRepository
     */
    public function __construct(UserInterface $userRepository)
    {
        $this->userRepository = $userRepository;

        Theme::asset()->add('my-account-style', 'vendor/core/css/my-account.css');
    }

    /**
     * @return \Response
     * @author Sang Nguyen
     */
    public function getOverview()
    {
        return Theme::of('acl::auth.my-account.overview')->render();
    }

    /**
     * @return \Response
     * @author Sang Nguyen
     */
    public function getEditAccount()
    {
        return Theme::of('acl::auth.my-account.edit-account')->render();
    }

    /**
     * @param EditAccountRequest $request
     * @return \Illuminate\Http\RedirectResponse
     * @author Sang Nguyen
     */
    public function postEditAccount(EditAccountRequest $request)
    {
        $this->userRepository->createOrUpdate($request->input(), ['id' => acl_get_current_user_id()]);
        set_user_meta('company', $request->input('company'));
        return redirect()->route('public.customer.edit-account')->with('success_msg', __('Update profile successfully!'));
    }

    /**
     * @return \Response
     * @author Sang Nguyen
     */
    public function getChangePassword()
    {
        return Theme::of('acl::auth.my-account.change-password')->render();
    }

    /**
     * @param UpdatePasswordRequest $request
     * @param ChangePasswordService $service
     * @return \Illuminate\Http\RedirectResponse
     * @author Sang Nguyen
     */
    public function postChangePassword(UpdatePasswordRequest $request, ChangePasswordService $service)
    {
        $result = $service->execute($request);

        if ($result instanceof Exception) {
            return redirect()->back()
                ->with('error_msg', $result->getMessage());
        }

        return redirect()->back()->with('success_msg', trans('acl::users.password_update_success'));
    }

    /**
     * @return \Response
     * @author Sang Nguyen
     */
    public function getChangeProfileImage()
    {
        return Theme::of('acl::auth.my-account.change-profile-image')->render();
    }

    /**
     * @author Sang Nguyen
     * @param ChangeProfileImageRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function postChangeProfileImage(ChangeProfileImageRequest $request)
    {
        $file = rv_media_handle_upload($request->file('profile_image'));
        $this->userRepository->createOrUpdate(['profile_image' => $file->url], ['id' => rv_media_get_current_user_id()]);
        return redirect()->back()->with('success_msg', __('Update avatar successfully!'));
    }
}