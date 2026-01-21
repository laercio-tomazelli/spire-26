<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

final class EnsurePermission
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): Response  $next
     * @param  string  ...$permissions  Required permissions (any of them)
     */
    public function handle(Request $request, Closure $next, string ...$permissions): Response
    {
        $user = $request->user();

        if (! $user) {
            return to_route('login');
        }

        // Check if user has any of the required permissions
        abort_unless($user->hasAnyPermission($permissions), 403, 'Você não tem permissão para acessar este recurso.');

        return $next($request);
    }
}
