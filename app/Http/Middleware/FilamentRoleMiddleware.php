<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class FilamentRoleMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (!auth()->check() || !auth()->user()->hasAnyRole(['admin', 'advogado'])) {
            abort(403, 'Acesso negado.');
        }

        return $next($request);
    }
}
