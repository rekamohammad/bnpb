<?php

namespace Botble\Base\Exceptions;

use EmailHandler;
use Exception;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Exceptions\PostTooLargeException;
use Monolog\Handler\SlackHandler;
use Monolog\Logger;
use RvMedia;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Theme;
use Symfony\Component\Debug\Exception\FlattenException;
use Symfony\Component\Debug\ExceptionHandler as SymfonyExceptionHandler;
use URL;

class Handler extends ExceptionHandler
{
    /**
     * Render an exception into an HTTP response.
     * @param \Illuminate\Http\Request $request
     * @param Exception $ex
     * @return \Response|\Symfony\Component\HttpFoundation\Response|array
     * @author Sang Nguyen
     */
    public function render($request, Exception $ex)
    {
        if ($ex instanceof PostTooLargeException) {
            return RvMedia::responseError(trans('media::media.upload_failed', [
                'size' => human_file_size(RvMedia::getServerConfigMaxUploadFileSize()),
            ]));
        }

        if ($ex instanceof ModelNotFoundException) {
            $ex = new NotFoundHttpException($ex->getMessage(), $ex);
        }

        if ($ex instanceof AuthorizationException) {

            if ($request->is(config('cms.admin_dir') . '/*') || $request->is(config('cms.admin_dir'))) {
                if ($request->ajax() || $request->wantsJson()) {
                    return [
                        'error' => true,
                        'message' => trans('acl::permissions.access_denied_message'),
                    ];
                }
                return response()->view('bases::errors.401', [], 403);
            }
            admin_bar()->setDisplay(false);
            return [
                'error' => true,
                'message' => trans('acl::permissions.access_denied_message'),
            ];
        }

        if ($this->isHttpException($ex)) {

            $code = $ex->getStatusCode();

            do_action(BASE_ACTION_SITE_ERROR, $code);

            // Handle permission error
            if (in_array($code, [401, 403])) {
                if ($request->is(config('cms.admin_dir') . '/*') || $request->is(config('cms.admin_dir'))) {
                    if ($request->ajax() || $request->wantsJson()) {
                        return [
                            'error' => true,
                            'message' => trans('acl::permissions.access_denied_message'),
                        ];
                    }
                    return response()->view('bases::errors.401', [], 401);
                }
                admin_bar()->setDisplay(false);
                return [
                    'error' => true,
                    'message' => trans('acl::permissions.access_denied_message'),
                ];
            }

            // Handle not found error
            if ($code == 404) {
                if ($request->is(config('cms.admin_dir') . '/*') || $request->is(config('cms.admin_dir'))) {
                    return response()->view('bases::errors.404', [], 404);
                }

                $theme = Theme::uses(setting('theme'))->layout(setting('layout', 'default'));
                Theme::breadcrumb()->add(__('Home'), route('public.index'))->add('404');
                return $theme->scope('errors.404')->render();
            }

            // Handle other errors
            if (in_array($code, [500, 503])) {
                if ($request->is(config('cms.admin_dir') . '/*') || $request->is(config('cms.admin_dir'))) {
                    return response()->view('bases::errors.500', [], 500);
                }
                $theme = Theme::uses(setting('theme'))->layout(setting('layout', 'default'));
                return $theme->scope('errors.500')->render();
            }
        }
        return parent::render($request, $ex);
    }

    /**
     * Report or log an exception.
     *
     * This is a great spot to send exceptions to Emails.
     *
     * @param  \Exception $exception
     * @return void
     */
    public function report(Exception $exception)
    {
        if ($this->shouldReport($exception) && config('cms.error_reporting.via_email') == true && !$this->isExceptionFromBot()) {
            EmailHandler::sendErrorException($exception);

            if (env('SLACK_REPORT_ENABLED')) {

                $ex = FlattenException::create($exception);

                $handler = new SymfonyExceptionHandler();

                $logger = new Logger('general');

                $logger->pushHandler(new SlackHandler(env('SLACK_TOKEN'), env('SLACK_CHANEL'), 'Botble BOT', true, ':helmet_with_white_cross:'));

                $logger->addCritical(URL::full() . "\n" . $exception->getFile() . ':' . $exception->getLine() . "\n" . $handler->getContent($ex));
            }
        }

        parent::report($exception);
    }


    /**
     * Determine if the exception is from the bot.
     *
     * @return boolean
     * @author Sang Nguyen
     */
    protected function isExceptionFromBot()
    {
        $ignored_bots = config('cms.error_reporting.ignored_bots');
        $agent = array_key_exists('HTTP_USER_AGENT', $_SERVER) ? strtolower($_SERVER['HTTP_USER_AGENT']) : null;
        if (empty($agent)) {
            return false;
        }
        foreach ($ignored_bots as $bot) {
            if ((strpos($agent, $bot) !== false)) {
                return true;
            }
        }
        return false;
    }
}
