<?php

namespace App\Http\Middleware;

use App\Support\AttendanceAccess;
use App\Support\ClosingEventAccess;
use Illuminate\Http\Request;
use Inertia\Middleware;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that is loaded on the first page visit.
     *
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determine the current asset version.
     */
    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    /**
     * Define the props that are shared by default.
     *
     * @return array<string, mixed>
     */
    public function share(Request $request): array
    {
        $closingEventPermissions = app(ClosingEventAccess::class)->for($request->user());
        $attendancePermissions = app(AttendanceAccess::class)->for($request->user());
        $cmsCanManage = in_array(
            $request->user()?->role()->value('nama_role'),
            ['admin', 'super_admin'],
            true,
        );

        return [
            ...parent::share($request),
            'auth' => [
                'user' => $request->user(),
                'closingEvent' => $closingEventPermissions,
                'attendance' => $attendancePermissions,
                'cms' => [
                    'canManage' => $cmsCanManage,
                ],
            ],
            'flash' => [
                'success' => fn (): ?string => $request->session()->get('success'),
                'error' => fn (): ?string => $request->session()->get('error'),
                'warning' => fn (): ?string => $request->session()->get('warning'),
            ],
        ];
    }
}
