<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class FilamentRoleMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (!auth()->check()) {
            abort(403, 'Acesso negado.');
        }

        $user = auth()->user();

        $roleToPath = [
            'admin' => 'admin',
            'advogado' => 'advogado',
            'cliente' => 'cliente',
        ];

        foreach ($roleToPath as $role => $path) {
            if ($user->hasRole($role) && $request->is($path . '*')) {
                return $next($request); // Permite o acesso
            }
        }
        abort(403, 'Você não tem permissão para acessar este painel.');
    }
}
