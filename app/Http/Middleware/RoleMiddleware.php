<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        // 401: belum terautentikasi
        if (! $request->user()) {
            abort(401);
        }

        // 403: sudah login tetapi role tidak diizinkan
        if (! in_array($request->user()->peran, $roles, true)) {
            abort(403, 'Anda tidak memiliki akses ke fitur ini.');
        }

        return $next($request);
    }
}
