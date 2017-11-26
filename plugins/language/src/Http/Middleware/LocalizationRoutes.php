<?php

namespace Botble\Language\Http\Middleware;

use Closure;
use Language;

class LocalizationRoutes
{

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     * @author Marc Cámara <mcamara88@gmail.com>
     */
    public function handle($request, Closure $next)
    {
        $routeName = Language::getRouteNameFromAPath($request->getUri());

        Language::setRouteName($routeName);

        return $next($request);
    }
}