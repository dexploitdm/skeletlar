<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        //Если человек залогинелся и при этом он Амин то пропускаем
        if(Auth::check() && Auth::user()->is_admin) {
            return $next($request);
        }
        abort(404);
    }
}
