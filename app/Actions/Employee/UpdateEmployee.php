<?php

namespace App\Actions\Employee;

use App\Models\Karyawan;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Throwable;

class UpdateEmployee
{
    public function __construct(private readonly SyncEmployeeAccountRole $syncAccountRole) {}

    public function handle(Karyawan $employee, array $data, ?UploadedFile $photo): Karyawan
    {
        $oldPath = $employee->foto_ktp;
        $newPath = $photo?->store('employee-ktp', 'local');
        $positionChanged = array_key_exists('jabatan_id', $data)
            && (int) $employee->jabatan_id !== (int) $data['jabatan_id'];

        try {
            DB::transaction(function () use ($employee, $data, $newPath, $positionChanged): void {
                unset($data['foto_ktp']);
                if ($newPath) {
                    $data['foto_ktp'] = $newPath;
                }
                $employee->update($data);
                if ($positionChanged) {
                    $this->syncAccountRole->handle($employee->refresh());
                }

                if ($employee->status_keaktifan === 'nonaktif') {
                    $employee->user()->update(['is_active' => false]);
                }
            });
        } catch (Throwable $exception) {
            if ($newPath) {
                Storage::disk('local')->delete($newPath);
            }

            throw $exception;
        }

        if ($newPath && $oldPath) {
            Storage::disk('local')->delete($oldPath);
        }

        return $employee->refresh();
    }
}
