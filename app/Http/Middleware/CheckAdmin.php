<?php

namespace App\Http\Middleware;

use Closure;

class CheckAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @param string $permission
     * @return mixed
     */
    public function handle($request, Closure $next, string $permission = '')
    {
        if (!$request->user()->is_admin || !$request->user()->admin)
        {
            return redirect('/');
        }

        if ($permission)
        {
            if (!$request->user()
                ->admin
                ->hasPermission($permission))
            {
                return redirect('admin/');
            }
        }

        return $next($request);
    }
}
