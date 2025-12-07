<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

final class EnsureActiveUser
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): Response  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        // Only check if user is authenticated
        if (! $user) {
            return $next($request);
        }

        // Check if authenticated user is active
        if (! $user->is_active) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            // Check if login route exists before redirecting
            if (resolve('router')->has('login')) {
                return to_route('login')
                    ->withErrors(['email' => 'Sua conta foi desativada. Entre em contato com o suporte.']);
            }

            abort(403, 'Sua conta foi desativada. Entre em contato com o suporte.');
        }

        return $next($request);
    }
}
