<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class OnlyForAdmin
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
        if(Auth::user()->isAdmin()) return $next($request);

        if($request->ajax()) return response('Только для админа', 401);

        return redirect()->back()->withErrors('У вас не достаточно прав для совершения этого действия');
    }
}
