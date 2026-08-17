<?php

namespace App\Actions\Employee;

use App\Models\Karyawan;
use Illuminate\Support\Facades\DB;

class ProcessEmployeeExit
{
    public function handle(Karyawan $employee, string $exitDate): void
    {
        DB::transaction(function () use ($employee, $exitDate): void {
            $employee->update([
                'tanggal_keluar' => $exitDate,
                'status_keaktifan' => 'nonaktif',
            ]);
            $employee->user()->update(['is_active' => false]);
        });
    }
}
