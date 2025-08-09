<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Auth\Middleware\EnsureEmailIsVerified;


class GuestOrVerified extends EnsureEmailIsVerified
{
       /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $redirectToRoute
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse|null
     */

    public function handle($request, Closure $next, $redirectToRoute = null)
    {
        if(!$request->user()) {
            return $next($request);
        }

        return parent::handle($request, $next, $redirectToRoute);
    }
}
