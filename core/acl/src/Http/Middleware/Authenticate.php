<?php

namespace Botble\ACL\Http\Middleware;

use Closure;
use DashboardMenu;

class Authenticate
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
        if (!acl_check_login()) {
            if ($request->ajax() || $request->wantsJson()) {
                return response('Unauthorized.', 401);
            } else {
                if ($request->is(config('cms.admin_dir') . '/*') || $request->is(config('cms.admin_dir'))) {
                    return redirect()->guest(route('access.login'));
                }
                return redirect()->guest(route('public.access.login'));
            }
        }

        $route = $request->route()->getAction();
        $flag = array_get($route, 'permission', array_get($route, 'as'));

        if ($flag && !acl_get_current_user()->hasPermission($flag)) {
            abort(401);
        }

        DashboardMenu::init($request->user());
        return $next($request);
    }
}
