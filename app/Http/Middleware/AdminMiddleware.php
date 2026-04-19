<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\User;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $user = User::find(session('user_id'));
        if (!$user || $user->role !== 'admin') {
            abort(403, 'Access denied. Admins only.');
        }
        return $next($request);
    }
}
