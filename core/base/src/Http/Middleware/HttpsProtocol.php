<?php

namespace Botble\Base\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class HttpsProtocol
{

    /**
     * @param Request $request
     * @param Closure $next
     * @return \Illuminate\Http\RedirectResponse
     * @author Sang Nguyen
     */
    public function handle($request, Closure $next)
    {
        if (!$request->secure() && config('cms.enable_https_support', false)) {
            return redirect()->secure($request->getRequestUri());
        }

        return $next($request);
    }
}
