<?php

namespace Botble\RequestLog\Listeners;

use Botble\RequestLog\Events\RequestHandlerEvent;
use Botble\RequestLog\Models\RequestLog;
use Request;

class RequestHandlerListener
{
    /**
     * @var mixed
     */
    public $requestLog;

    /**
     * RequestHandlerListener constructor.
     * @param RequestLog $requestLog
     * @author Sang Nguyen
     */
    public function __construct(RequestLog $requestLog)
    {
        $this->requestLog = $requestLog;
    }

    /**
     * Handle the event.
     *
     * @param  RequestHandlerEvent $event
     * @return void
     * @author Sang Nguyen
     */
    public function handle(RequestHandlerEvent $event)
    {
        $this->requestLog = RequestLog::firstOrNew([
            'url' => substr(Request::fullUrl(), 0, 255),
            'status_code' => $event->code,
        ]);

        if ($referrer = Request::header('referrer')) {
            $referrers = (array)$this->requestLog->referer ?: [];
            $referrers[] = $referrer;
            $this->requestLog->referer = $referrers;
        }

        if (acl_check_login()) {
            if (!is_array($this->requestLog->user_id)) {
                $this->requestLog->user_id = [acl_get_current_user_id()];
            } else {
                $this->requestLog->user_id = array_unique(array_merge($this->requestLog->user_id, [acl_get_current_user_id()]));
            }
        }

        if (!$this->requestLog->exists) {
            $this->requestLog->count = 1;
        } else {
            $this->requestLog->count += 1;
        }

        $this->requestLog->save();
    }
}
