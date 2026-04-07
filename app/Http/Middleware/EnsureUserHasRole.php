<?php

namespace App\Http\Middleware;

use App\Enums\UserRole;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserHasRole
{
    /**
     * @param  Closure(Request): (Response)  $next
     * @param  string  ...$roles  Role names, e.g. admin, manager (from route: role:admin,manager)
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        $user = $request->user();

        if (! $user) {
            abort(Response::HTTP_FORBIDDEN);
        }

        // Admins can access any route that uses this middleware (full app access).
        if ($user->isAdmin()) {
            return $next($request);
        }

        $allowed = collect($roles)
            ->flatMap(fn (string $r) => array_map('trim', explode(',', $r)))
            ->map(fn (string $r) => UserRole::tryFrom($r))
            ->filter()
            ->values();

        if ($allowed->isEmpty() || ! $allowed->contains($user->role)) {
            abort(Response::HTTP_FORBIDDEN, 'This action is unauthorized.');
        }

        return $next($request);
    }
}
