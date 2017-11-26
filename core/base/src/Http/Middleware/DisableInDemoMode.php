<?php

namespace Botble\Base\Http\Middleware;

use Closure;

class DisableInDemoMode
{

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     * @author Sang Nguyen
     * @since 2.1
     */
    public function handle($request, Closure $next)
    {
        if (app()->environment() == 'demo') {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'error' => true,
                    'message' => trans('bases::system.disabled_in_demo_mode'),
                ]);
            }
            return redirect()->back()->with('error_msg', trans('bases::system.disabled_in_demo_mode'))->withInput();
        }

        return $next($request);
    }
}
