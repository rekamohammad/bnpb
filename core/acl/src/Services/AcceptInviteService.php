<?php

namespace Botble\ACL\Services;

use Botble\ACL\Events\RoleAssignmentEvent;
use Botble\ACL\Models\User;
use Botble\ACL\Repositories\Interfaces\InviteInterface;
use Botble\ACL\Repositories\Interfaces\RoleInterface;
use Botble\ACL\Repositories\Interfaces\RoleUserInterface;
use Botble\ACL\Repositories\Interfaces\UserInterface;
use Botble\Support\Services\ProduceServiceInterface;
use Exception;
use Illuminate\Http\Request;
use Sentinel;

class AcceptInviteService implements ProduceServiceInterface
{
    /**
     * @var UserInterface
     */
    protected $userRepository;

    /**
     * @var InviteInterface
     */
    protected $inviteRepository;

    /**
     * @var RoleInterface
     */
    protected $roleRepository;

    /**
     * @var RoleUserInterface
     */
    protected $roleUserRepository;

    /**
     * AcceptInviteService constructor.
     * @param UserInterface $userRepository
     * @param InviteInterface $inviteRepository
     * @param RoleInterface $roleRepository
     * @param RoleUserInterface $roleUserRepository
     */
    public function __construct(
        UserInterface $userRepository,
        InviteInterface $inviteRepository,
        RoleInterface $roleRepository,
        RoleUserInterface $roleUserRepository)
    {
        $this->userRepository = $userRepository;
        $this->inviteRepository = $inviteRepository;
        $this->roleRepository = $roleRepository;
        $this->roleUserRepository = $roleUserRepository;
    }

    /**
     * @param Request $request
     * @return bool|\Exception
     * @author Sang Nguyen
     */
    public function execute(Request $request)
    {
        $token = $request->input('token');

        if (empty($token)) {
            return new Exception(trans('acl::users.invite_not_exist'));
        }

        $invite = $this->inviteRepository->getFirstBy(['token' => $token, 'accepted' => false]);

        $user = $this->userRepository->findById($invite->invitee_id);
        if (!$user) {
            return new Exception(trans('acl::users.invite_not_exist'));
        }

        $exist_username = $this->userRepository->count(['username' => $request->input('username'), ['id', '<>', $user->id]]);
        if ($exist_username > 0) {
            return new Exception(__('This username is existed, please choose another one!'));
        }

        $credentials = [
            'username' => $request->input('username'),
            'password' => $request->input('password'),
        ];

        if (Sentinel::getUserRepository()->validForCreation($credentials)) {
            /**
             * @var User $user
             */
            $user = Sentinel::getUserRepository()->update($user, $credentials);

            if (acl_activate_user($user)) {

                $role = $this->roleRepository->getFirstBy([
                    'id' => $invite->role_id,
                ]);

                if (!empty($role)) {
                    $this->roleUserRepository->firstOrCreate([
                        'user_id' => $user->id,
                        'role_id' => $invite->role_id,
                    ]);

                    event(new RoleAssignmentEvent($role, $user));
                }

                $invite->accepted = true;
                $this->inviteRepository->createOrUpdate($invite);
            }

            Sentinel::authenticateAndRemember($credentials);
            return true;
        }

        return new Exception(__('Validate invite link failed!'));
    }
}