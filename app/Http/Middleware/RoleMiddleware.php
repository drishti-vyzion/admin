<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
        public function handle(Request $request, Closure $next, $role): Response
        {
            if (!Auth::user() || Auth::user()->role !== $role) {
                abort(403, 'Unauthorized action.');
            }
// dd($next($request));
            return $next($request);
        }
}

