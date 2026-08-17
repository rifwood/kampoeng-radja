<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserIsAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): Response  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (! $user?->is_active || $user->karyawan()->value('status_keaktifan') !== 'aktif') {
            abort(Response::HTTP_FORBIDDEN);
        }

        $roleName = $user->role()->value('nama_role');

        if (! in_array($roleName, ['admin', 'super_admin'], true)) {
            abort(Response::HTTP_FORBIDDEN);
        }

        return $next($request);
    }
}
