<?php

namespace App\Actions\Employee;

use App\Models\Karyawan;
use Illuminate\Support\Facades\DB;

class DeactivateEmployee
{
    public function handle(Karyawan $employee): void
    {
        DB::transaction(function () use ($employee): void {
            $employee->update(['status_keaktifan' => 'nonaktif']);
            $employee->user()->update(['is_active' => false]);
        });
    }
}
