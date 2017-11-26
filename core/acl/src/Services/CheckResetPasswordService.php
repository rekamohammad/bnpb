<?php

namespace Botble\ACL\Services;

use Botble\ACL\Repositories\Interfaces\UserInterface;
use Botble\Support\Services\ProduceServiceInterface;
use Exception;
use Illuminate\Http\Request;
use Sentinel;

class CheckResetPasswordService implements ProduceServiceInterface
{
    /**
     * @var UserInterface
     */
    protected $userRepository;

    /**
     * CheckResetPasswordService constructor.
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
        $username = $request->input('username');

        $user = $this->userRepository->getFirstBy(['username' => $username]);
        if (!$user) {
            return new Exception(trans('acl::auth.reset.user_not_found'));
        }
        Sentinel::getReminderRepository()->removeExpired();
        $token = $request->input('token');

        if (empty($token) || !Sentinel::getReminderRepository()->exists($user, $token)) {
            return new Exception(trans('acl::auth.reset.fail'));
        }
        return $user;
    }
}