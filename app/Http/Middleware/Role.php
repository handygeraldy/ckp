<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class Role
{
    public function handle($request, Closure $next, $batas_bawah)
    {
        $role = Auth::check() ? Auth::user()->role_id : 99;
        if ((int)$role <= (int)$batas_bawah ) {
            return $next($request);
        }
        return redirect()->intended('/');
    }
}
