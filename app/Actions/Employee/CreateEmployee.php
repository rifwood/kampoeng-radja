<?php

namespace App\Actions\Employee;

use App\Models\Karyawan;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Throwable;

class CreateEmployee
{
    public function handle(array $data, ?UploadedFile $photo): Karyawan
    {
        $path = $photo?->store('employee-ktp', 'local');

        try {
            return DB::transaction(function () use ($data, $path): Karyawan {
                unset($data['foto_ktp']);

                return Karyawan::create([...$data, 'foto_ktp' => $path ?: null]);
            });
        } catch (Throwable $exception) {
            if ($path) {
                Storage::disk('local')->delete($path);
            }

            throw $exception;
        }
    }
}
