<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminMiddleware
{
    public function handle($request, Closure $next)
{
    if (!auth()->check() || !auth()->user()->hasRole('admin')) {
        abort(403, 'Unauthorized');
    }
    return $next($request);
}

}
