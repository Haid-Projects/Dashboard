<?php

namespace App\Http\Middleware;

use App\Traits\GeneralTrait;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AssignGuard
{
    use GeneralTrait;
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     */
    public function handle(Request $request, Closure $next, $guard)
    {
        if(Auth::guard($guard)->user())
            return $next($request);
        return $this->returnErrorMessage('please sign in first', 403);
    }
}
