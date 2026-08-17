<?php

namespace Tests\Unit;

use App\Actions\Employee\ResolveEmployeeAccountRole;
use PHPUnit\Framework\TestCase;

class ResolveEmployeeAccountRoleTest extends TestCase
{
    public function test_super_admin_positions_are_resolved(): void
    {
        $this->assertRoleForPositions('super_admin', [
            'Dirut',
            'Direktur',
            'Direktur Utama',
            'Direktur Operasional',
            'Admin Sistem',
        ]);
    }

    public function test_admin_positions_are_resolved(): void
    {
        $this->assertRoleForPositions('admin', [
            'Manajer Marketing',
            'Manager Marketing',
            'Manajer Operasional',
            'Supervisor',
            'Supervisor Operasional',
            'Supervisor Facility',
        ]);
    }

    public function test_user_positions_are_resolved(): void
    {
        $this->assertRoleForPositions('user', [
            'Mitra',
            'Operasional',
            'OPS',
            'Facility',
            'FLT',
        ]);
    }

    public function test_hierarchy_has_priority_over_organizational_group(): void
    {
        $this->assertRoleForPositions('super_admin', ['Direktur Operasional']);
        $this->assertRoleForPositions('admin', [
            'Manajer Operasional',
            'Supervisor Operasional',
            'Supervisor Facility',
            'Manajer Facility',
        ]);
    }

    public function test_normalization_is_case_insensitive_and_trims_whitespace(): void
    {
        $this->assertRoleForPositions('admin', [
            'Supervisor Operasional',
            'SUPERVISOR OPERASIONAL',
            '  supervisor   operasional  ',
        ]);
    }

    public function test_unmapped_positions_do_not_receive_fallback_role(): void
    {
        $this->assertRoleForPositions(null, [
            'Operator Wahana',
            'Staff Ticketing',
            'Teknisi Maintenance',
            'Staff Marketing',
            null,
            '',
        ]);
    }

    /** @param array<int, string|null> $positions */
    private function assertRoleForPositions(?string $expectedRole, array $positions): void
    {
        $resolver = new ResolveEmployeeAccountRole;

        foreach ($positions as $position) {
            $this->assertSame($expectedRole, $resolver->handle($position), (string) $position);
        }
    }
}
