<?php

namespace Botble\ACL\Listeners;

use Botble\ACL\Events\RoleAssignmentEvent;
use Botble\ACL\Repositories\Interfaces\UserInterface;

class RoleAssignmentListener
{
    /**
     * @var UserInterface
     */
    protected $userRepository;

    /**
     * RoleAssignmentListener constructor.
     * @author Sang Nguyen
     * @param UserInterface $userRepository
     */
    public function __construct(UserInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * Handle the event.
     *
     * @param  RoleAssignmentEvent $event
     * @return void
     * @author Sang Nguyen
     */
    public function handle(RoleAssignmentEvent $event)
    {
        info('Role ' . $event->role->name . ' assigned to user ' . $event->user->getFullName());
        $permissions = [];
        foreach ($event->role->flags()->get() as $flag) {
            $permissions[$flag->flag] = true;
        }
        // Insert permission flag
        $user_permissions = [];
        if ($event->user->super_user) {
            $user_permissions['superuser'] = true;
        } else {
            $user_permissions['superuser'] = false;
        }
        if ($event->user->manage_supers) {
            $user_permissions['manage_supers'] = true;
        } else {
            $user_permissions['manage_supers'] = false;
        }

        $this->userRepository->update([
            'id' => $event->user->id,
        ], [
            'permissions' => json_encode(array_merge($permissions, $user_permissions)),
        ]);

        cache()->forget(md5('cache-dashboard-menu'));
    }
}
