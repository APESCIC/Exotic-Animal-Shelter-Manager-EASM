<?php

namespace App\Http\Middleware;

use App\Enums\UserRole;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserHasRole
{
    /**
     * Allow only authenticated users whose role is in the given list.
     *
     * @param  Closure(Request): Response  $next
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        $user = $request->user();

        if ($user === null) {
            return redirect()->guest(route('login'));
        }

        $allowed = [];

        foreach ($roles as $role) {
            $allowed[] = UserRole::from($role);
        }

        if (! in_array($user->role, $allowed, true)) {
            abort(403, 'You do not have permission to view this page.');
        }

        return $next($request);
    }
}
