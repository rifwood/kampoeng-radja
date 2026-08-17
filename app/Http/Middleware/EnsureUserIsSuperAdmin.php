<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserIsSuperAdmin
{
    /**
     * @param  Closure(Request): Response  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (! $user?->is_active
            || $user->karyawan()->value('status_keaktifan') !== 'aktif'
            || $user->role()->value('nama_role') !== 'super_admin') {
            abort(Response::HTTP_FORBIDDEN);
        }

        return $next($request);
    }
}
