<?php

namespace Botble\Language\Http\Middleware;

use Illuminate\Http\RedirectResponse;
use Closure;
use Language;

class LocaleSessionRedirect
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
        $params = explode('/', $request->path());
        $locale = session('language', false);

        if (count($params) > 0 && Language::checkLocaleInSupportedLocales($params[0])) {
            session(['language' => $params[0]]);

            app()->setLocale(session()->get('language'));

            return $next($request);
        }

        if ($locale && Language::checkLocaleInSupportedLocales($locale) && !(Language::getDefaultLocale() === $locale && Language::hideDefaultLocaleInURL())) {
            app('session')->reflash();
            $redirection = Language::getLocalizedURL($locale);

            return new RedirectResponse($redirection, 302, ['Vary' => 'Accept-Language']);
        }

        return $next($request);
    }
}
