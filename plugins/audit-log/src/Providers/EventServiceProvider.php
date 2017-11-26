<?php

namespace Botble\AuditLog\Providers;

use Botble\AuditLog\Events\AuditHandlerEvent;
use Botble\AuditLog\Listeners\AuditHandlerListener;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        AuditHandlerEvent::class => [
            AuditHandlerListener::class,
        ],
    ];
}
