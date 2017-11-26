<?php

namespace Botble\ACL\Http\Middleware;

use Closure;

class RedirectIfAuthenticated
{

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  Closure $next
     * @return mixed
     * @author Sang Nguyen
     */
    public function handle($request, Closure $next)
    {
        if (acl_check_login()) {
            if (acl_get_current_user()->hasPermission('dashboard.index')) {
                return redirect(route('dashboard.index'));
            }
            return redirect()->route('public.index');
        }

        return $next($request);
    }
}
