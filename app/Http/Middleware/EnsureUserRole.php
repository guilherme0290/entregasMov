<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserRole
{
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        $user = $request->user();

        abort_if(! $user, Response::HTTP_UNAUTHORIZED, 'Usuário não autenticado.');
        abort_if(! in_array($user->role->value, $roles, true), Response::HTTP_FORBIDDEN, 'Acesso não autorizado para este perfil.');

        return $next($request);
    }
}
