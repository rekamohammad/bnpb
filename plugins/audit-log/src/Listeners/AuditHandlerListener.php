<?php

namespace Botble\AuditLog\Listeners;

use Botble\AuditLog\Events\AuditHandlerEvent;
use Botble\AuditLog\Models\AuditHistory;
use Request;

class AuditHandlerListener
{
    /**
     * @var mixed
     */
    public $auditHistory;

    /**
     * AuditHandlerListener constructor.
     * @param AuditHistory $auditHistory
     * @author Sang Nguyen
     */
    public function __construct(AuditHistory $auditHistory)
    {
        $this->auditHistory = $auditHistory;
    }

    /**
     * Handle the event.
     *
     * @param  AuditHandlerEvent $event
     * @return void
     * @author Sang Nguyen
     */
    public function handle(AuditHandlerEvent $event)
    {
        if (isset($_SERVER['HTTP_USER_AGENT'])) {
            $this->auditHistory->user_agent = $_SERVER['HTTP_USER_AGENT'];
        }

        if (isset($_SERVER['REMOTE_ADDR'])) {
            $this->auditHistory->ip_address = $_SERVER['REMOTE_ADDR'];
        }

        if ($event->action !== 'loggedin' && $event->action !== 'password') {
            $this->auditHistory->request = json_encode(Request::all());
        }

        $this->auditHistory->module = $event->module;
        $this->auditHistory->action = $event->action;
        $this->auditHistory->user_id = acl_get_current_user_id();
        $this->auditHistory->reference_user = $event->reference_user;
        $this->auditHistory->reference_id = $event->reference_id;
        $this->auditHistory->reference_name = $event->reference_name;
        $this->auditHistory->type = $event->type;

        $this->auditHistory->save();
    }
}
