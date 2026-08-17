<?php

namespace App\Actions\Employee;

use Illuminate\Support\Str;

class ResolveEmployeeAccountRole
{
    private const SUPER_ADMIN_TOKENS = ['dirut', 'direktur'];

    private const ADMIN_TOKENS = ['manajer', 'manager', 'supervisor'];

    private const USER_TOKENS = ['mitra', 'operasional', 'ops', 'facility', 'flt'];

    public function handle(?string $positionName): ?string
    {
        $normalized = Str::of($positionName ?? '')
            ->lower()
            ->ascii()
            ->replaceMatches('/[^a-z0-9]+/', ' ')
            ->squish()
            ->toString();

        if ($normalized === '') {
            return null;
        }

        $tokens = preg_split('/\s+/', $normalized) ?: [];

        // Hierarchy must be resolved before organizational groups. For example,
        // "Direktur Operasional" is a super admin, not a user.
        if (array_intersect($tokens, self::SUPER_ADMIN_TOKENS)
            || str_contains($normalized, 'admin sistem')) {
            return 'super_admin';
        }

        if (array_intersect($tokens, self::ADMIN_TOKENS)) {
            return 'admin';
        }

        if (array_intersect($tokens, self::USER_TOKENS)) {
            return 'user';
        }

        return null;
    }
}
