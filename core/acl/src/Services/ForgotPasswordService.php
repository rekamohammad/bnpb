<?php

namespace Botble\ACL\Services;

use Botble\ACL\Repositories\Interfaces\UserInterface;
use Botble\Support\Services\ProduceServiceInterface;
use EmailHandler;
use Exception;
use Illuminate\Http\Request;
use Sentinel;

class ForgotPasswordService implements ProduceServiceInterface
{
    /**
     * @var UserInterface
     */
    protected $userRepository;

    /**
     * ForgotPasswordService constructor.
     * @param UserInterface $userRepository
     */
    public function __construct(UserInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * @param Request $request
     * @return bool|\Exception
     * @author Sang Nguyen
     */
    public function execute(Request $request)
    {
        $user = $this->userRepository->getFirstBy(['username' => $request->input('username')]);
        if (!$user) {
            return new Exception(trans('acl::auth.reset.user_not_found'));
        }

        /**
         * @var \Cartalyst\Sentinel\Reminders\EloquentReminder $reminder
         */
        $reminder = Sentinel::getReminderRepository()->create($user);
        if (Sentinel::getReminderRepository()->exists($user)) {
            $route = $request->is(config('cms.admin_dir') . '/*') ? 'access.reset-password' : 'public.access.reset-password';
            $data = [
                'user' => $user->username,
                'name' => $user->getFullName(),
                'email' => $user->email,
                'link' => route($route, ['username' => $user->username, 'token' => $reminder->code])
            ];

            try {
                EmailHandler::send(view('acl::emails.reminder', $data)->render(), trans('acl::auth.reset.title'), [
                    'name' => $user->getFullName(),
                    'to' => $user->email,
                ]);

            } catch (Exception $ex) {
                info($ex->getMessage());
                return $ex;
            }
        }
        return true;
    }
}