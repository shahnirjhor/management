<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckCompanyId
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if (empty(session('company_id'))
            && (!$request->is('company*') && !$request->is('login') && !$request->is('logout*'))) {
            return redirect('company');
        }

        if (sizeof($request->route()->parameters) == 1) {
            // $companyId = reset($request->route()->parameters)->company_id;
            $companyId = 1;
            if (!empty($companyId) && $companyId != session('company_id'))
                return redirect('dashboard');
        }

        return $next($request);
    }
}
