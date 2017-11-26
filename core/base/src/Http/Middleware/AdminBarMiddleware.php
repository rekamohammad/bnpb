<?php

namespace Botble\Base\Http\Middleware;

use Closure;
use HTML;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminBarMiddleware
{
    /**
     * @var \Illuminate\Foundation\Application|mixed
     */
    protected $app;

    /**
     * AdminBarMiddleware constructor.
     */
    public function __construct()
    {
        $this->app = app();
    }

    /**
     * Handle an incoming request.
     *
     * @param  Request $request
     * @param  \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $response = $next($request);
        if ($request->user() && $request->user()->hasPermission('dashboard.index') && admin_bar()->getDisplay()) {
            if (!!(int)setting('show_admin_bar', 1)) {
                $this->modifyResponse($request, $response);
            }
        }

        return $response;
    }

    /**
     * Modify the response and inject the admin bar
     *
     * @param  Request $request
     * @param  Response $response
     * @return Response
     */
    public function modifyResponse(Request $request, Response $response)
    {

        if (is_in_admin()
            || $this->app->runningInConsole()
            || $this->isDebugbarRequest()
            || $request->ajax()
            || $request->wantsJson()
            || $response->headers->get('Content-Type') == 'application/json'
        ) {
            return $response;
        }

        $this->injectAdminBar($response);

        return $response;
    }

    /**
     * Check if this is a request to the Debugbar OpenHandler
     * @return bool
     */
    protected function isDebugbarRequest()
    {
        return $this->app['request']->segment(1) == '_debugbar';
    }

    /**
     * Injects the admin bar into the given Response.
     * @param Response $response
     * Based on https://github.com/symfony/WebProfilerBundle/blob/master/EventListener/WebDebugToolbarListener.php
     */
    public function injectAdminBar(Response $response)
    {
        $content = $response->getContent();

        $this->injectHeadContent($content)->injectAdminBarHtml($content);

        // Update the new content and reset the content length
        $response->setContent($content);
        $response->headers->remove('Content-Length');
    }

    /**
     * @param $content
     * @return $this
     */
    public function injectHeadContent(&$content)
    {
        $css = HTML::style('vendor/core/css/admin-bar.css');
        $pos = strripos($content, '</head>');
        if (false !== $pos) {
            $content = substr($content, 0, $pos) . $css . substr($content, $pos);
        } else {
            $content = $content . $css;
        }
        return $this;
    }

    /**
     * @param $content
     * @return $this
     */
    public function injectAdminBarHtml(&$content)
    {
        $html = admin_bar()->render();
        $pos = strripos($content, '</body>');
        if (false !== $pos) {
            $content = substr($content, 0, $pos) . $html . substr($content, $pos);
        } else {
            $content = $content . $html;
        }
        return $this;
    }
}
