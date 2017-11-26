<?php

namespace Botble\ACL\Services;

use Botble\ACL\Models\User;
use Botble\ACL\Repositories\Interfaces\UserInterface;
use Botble\Support\Services\ProduceServiceInterface;
use EmailHandler;
use Exception;
use Illuminate\Http\Request;
use Sentinel;

class RegisterService implements ProduceServiceInterface
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
     * @param Request $request
     * @author Sang Nguyen
     * @return bool|Exception|mixed
     */
    public function execute(Request $request)
    {
        try {
            $user = $this->userRepository->createOrUpdate([
                'email' => $request->input('email'),
                'first_name' => $request->input('first_name'),
                'last_name' => $request->input('last_name'),
                'profile_image' => config('acl.avatar.default'),
            ]);

            $credentials = [
                'username' => $request->input('username'),
                'password' => $request->input('password'),
            ];

            if (Sentinel::getUserRepository()->validForCreation($credentials)) {
                /**
                 * @var User $user
                 */
                $user = Sentinel::getUserRepository()->update($user, $credentials);
                $activation = Sentinel::getActivationRepository()->create($user);

                EmailHandler::send(view('acl::emails.activation', compact('user', 'activation'))->render(), __('Activate account'), [
                    'name' => $user->getFullName(),
                    'to' => $user->email,
                ]);

                return true;
            }
        } catch (Exception $exception) {
            return $exception;
        }

        return new Exception(__('Can not register on this time, please try again later!'));
    }
}