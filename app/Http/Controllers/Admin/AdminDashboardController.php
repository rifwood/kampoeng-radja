<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class AdminDashboardController extends Controller
{
    public function __invoke(Request $request): Response
    {
        $user = $request->user()->loadMissing(['karyawan', 'role']);

        return Inertia::render('Admin/Dashboard', [
            'employeeName' => $user->karyawan->nama,
            'roleName' => $user->role->nama_role,
        ]);
    }
}
