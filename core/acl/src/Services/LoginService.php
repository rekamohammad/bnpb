<?php

namespace Botble\ACL\Services;

use Assets;
use Botble\ACL\Models\User;
use Botble\ACL\Models\UserMeta;
use Botble\Support\Services\ProduceServiceInterface;
use Cartalyst\Sentinel\Checkpoints\NotActivatedException;
use Cartalyst\Sentinel\Checkpoints\ThrottlingException;
use Illuminate\Http\Request;
use Sentinel;

class LoginService implements ProduceServiceInterface
{
    /**
     * @param Request $request
     * @return bool|\Exception
     * @author Sang Nguyen
     */
    public function execute(Request $request)
    {
        try {
            $credentials = [
                'username' => $request->input('username'),
                'password' => $request->input('password'),
            ];
            $remember = $request->input('remember') == 1 ? true : false;

            /**
             * @var User $user
             */
            $user = Sentinel::authenticate($credentials, $remember);
            if ($user) {

                if ($user->hasPermission('dashboard.index')) {
                    $locale = UserMeta::getMeta('admin-locale', false);

                    if ($locale != false && array_key_exists($locale, Assets::getAdminLocales())) {
                        app()->setLocale($locale);
                        session()->put('admin-locale', $locale);
                    }

                    if (!session()->has('url.intended')) {
                        session()->flash('url.intended', url()->current());
                    }
                    cache()->forget(md5('cache-dashboard-menu'));
                    do_action(AUTH_ACTION_AFTER_LOGIN_SYSTEM, AUTH_MODULE_SCREEN_NAME, request(), acl_get_current_user());
                }
                return true;
            }
        } catch (ThrottlingException $exception) {
            return $exception;
        }  catch (NotActivatedException $exception) {
            return $exception;
        }

        return false;
    }
}