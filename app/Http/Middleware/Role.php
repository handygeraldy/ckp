<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class Role
{
    public function handle($request, Closure $next, $roleIds)
    {
        $roleIds = explode("|", $roleIds);
        if (!in_array(Auth::user()->role_id, $roleIds)) {
            return response()->json(['success' => false, 'message' => 'Unauthorized user.'], 403);
        }
        return $next($request);
    }
}
