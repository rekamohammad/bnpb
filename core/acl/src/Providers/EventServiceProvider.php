<?php

namespace Botble\ACL\Providers;

use Botble\ACL\Events\RoleAssignmentEvent;
use Botble\ACL\Events\RoleUpdateEvent;
use Botble\ACL\Listeners\RoleAssignmentListener;
use Botble\ACL\Listeners\RoleUpdateListener;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     * @author Sang Nguyen
     */
    protected $listen = [
        RoleUpdateEvent::class => [
            RoleUpdateListener::class,
        ],
        RoleAssignmentEvent::class => [
            RoleAssignmentListener::class,
        ],
    ];
}
