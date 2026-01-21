<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use App\Enums\UserType;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

final class EnsureUserType
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): Response  $next
     * @param  string  ...$types  Allowed user types (e.g., 'spire', 'partner')
     */
    public function handle(Request $request, Closure $next, string ...$types): Response
    {
        $user = $request->user();

        if (! $user) {
            return to_route('login');
        }

        // Convert string types to enum values
        $allowedTypes = array_map(
            UserType::from(...),
            $types,
        );

        abort_unless(in_array($user->user_type, $allowedTypes, true), 403, 'Acesso não autorizado para este tipo de usuário.');

        return $next($request);
    }
}
