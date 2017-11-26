<?php

namespace Botble\Base\Supports;

use Botble\Base\Events\SendMailEvent;
use Exception;
use Symfony\Component\Debug\Exception\FlattenException;
use Symfony\Component\Debug\ExceptionHandler as SymfonyExceptionHandler;
use URL;

class EmailHandler
{

    /**
     * @param $view
     * @author Sang Nguyen
     */
    public function setEmailTemplate($view)
    {
        config()->set('cms.email_template', $view);
    }

    /**
     * @param $content
     * @param $title
     * @param $args
     * @author Sang Nguyen
     */
    public function send($content, $title, $args)
    {
        try {
            event(new SendMailEvent($content, $title . ' - ' . setting('site_title'), $args));
        } catch (Exception $ex) {
            info($ex->getMessage());
            $this->sendErrorException($ex);
        }
    }

    /**
     * Sends an email to the developer about the exception.
     *
     * @param  \Exception  $exception
     * @return void
     * @author Sang Nguyen
     */
    public function sendErrorException(Exception $exception)
    {
        try {
            $ex = FlattenException::create($exception);

            $handler = new SymfonyExceptionHandler();

            $url = URL::full();
            $error = $handler->getContent($ex);

            EmailHandler::send(view('bases::emails.error-reporting', compact('url', 'ex', 'error'))->render(), $exception->getFile(),
                [
                    'to' => !empty(config('cms.error_reporting.to')) ? config('cms.error_reporting.to') : setting('admin_email'),
                    'name' => setting('site_title'),
                ]
            );
        } catch (Exception $ex) {
            info($ex->getMessage());
        }
    }
}