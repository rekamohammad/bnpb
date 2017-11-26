<?php

namespace Botble\ACL\Services;

use Botble\ACL\Repositories\Interfaces\UserInterface;
use Exception;
use Sentinel;

class ActivateUserService
{
    /**
     * @var UserInterface
     */
    protected $userRepository;

    /**
     * RegisterService constructor.
     * @param UserInterface $userRepository
     */
    public function __construct(UserInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * @author Sang Nguyen
     * @param $code
     * @param $username
     * @return bool|Exception
     */
    public function execute($code, $username)
    {
        try {
            $user = $this->userRepository->getFirstBy(['username' => $username]);

            if (!$user) {
                return new Exception(__('User is not exists!'));
            }

            if (Sentinel::getActivationRepository()->complete($user, $code)) {
                Sentinel::loginAndRemember($user);
                return true;
            }

            return new Exception(__('Activation code is invalid or expired!'));
        } catch (Exception $exception) {
            return $exception;
        }
    }
}