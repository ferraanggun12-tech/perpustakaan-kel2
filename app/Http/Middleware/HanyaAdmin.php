<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class HanyaAdmin
{
    public function handle(Request $request, Closure $next)
    {
        abort_unless($request->user() && $request->user()->isAdmin(), 403, 'Halaman ini hanya untuk admin.');

        return $next($request);
    }
}
