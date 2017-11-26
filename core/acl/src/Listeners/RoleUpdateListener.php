<?php

namespace Botble\ACL\Listeners;

use Botble\ACL\Events\RoleUpdateEvent;
use Botble\ACL\Repositories\Interfaces\UserInterface;

class RoleUpdateListener
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
     * @param  RoleUpdateEvent $event
     * @return void
     * @author Sang Nguyen
     */
    public function handle(RoleUpdateEvent $event)
    {
        info('Role ' . $event->role->name . ' updated; rebuilding permission sets');
        $permissions = [];
        foreach ($event->role->flags()->get() as $flag) {
            $permissions[$flag->flag] = true;
        }
        foreach ($event->role->users()->get() as $user) {
            $user_permissions = [];
            if ($user->super_user) {
                $user_permissions['superuser'] = true;
            } else {
                $user_permissions['superuser'] = false;
            }
            if ($user->manage_supers) {
                $user_permissions['manage_supers'] = true;
            } else {
                $user_permissions['manage_supers'] = false;
            }
            $this->userRepository->update([
                'id' => $user->id,
            ], [
                'permissions' => json_encode(array_merge($permissions, $user_permissions)),
            ]);
        }
        cache()->forget(md5('cache-dashboard-menu'));
    }
}
