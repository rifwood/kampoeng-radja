<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Http\Requests\Employee\StoreDepartemenRequest;
use App\Http\Requests\Employee\UpdateDepartemenRequest;
use App\Models\Departemen;
use Illuminate\Http\RedirectResponse;

class DepartemenController extends Controller
{
    public function store(StoreDepartemenRequest $request): RedirectResponse
    {
        Departemen::create($request->validated());

        return back()->with('success', 'Departemen berhasil ditambahkan.');
    }

    public function update(UpdateDepartemenRequest $request, Departemen $departemen): RedirectResponse
    {
        $departemen->update($request->validated());

        return back()->with('success', 'Departemen berhasil diperbarui.');
    }

    public function destroy(Departemen $departemen): RedirectResponse
    {
        if ($departemen->karyawan()->exists()) {
            return back()->with('error', 'Tidak dapat dihapus karena data masih digunakan.');
        }
        $departemen->delete();

        return back()->with('success', 'Departemen berhasil dihapus.');
    }
}
