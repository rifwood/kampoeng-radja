<?php

namespace App\Http\Controllers\ClosingEvent;

use App\Actions\ClosingEvent\CreateClosingEvent;
use App\Actions\ClosingEvent\UpdateClosingEvent;
use App\Exports\ClosingEvent\ClosingEventsExport;
use App\Http\Controllers\Controller;
use App\Http\Requests\ClosingEvent\StoreClosingEventRequest;
use App\Http\Requests\ClosingEvent\UpdateClosingEventRequest;
use App\Models\ClosingEvent;
use App\Models\JenisEvent;
use App\Models\Lokasi;
use App\Models\Pic;
use App\Support\ClosingEventAccess;
use Carbon\CarbonImmutable;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Inertia\Inertia;
use Inertia\Response;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class ClosingEventController extends Controller
{
    public function index(Request $request, ClosingEventAccess $access): Response
    {
        $permissions = $this->authorizeCapability($request, $access, 'canView');
        $today = CarbonImmutable::today('Asia/Jakarta');
        $validated = $request->validate([
            'bulan' => ['nullable', 'integer', 'between:1,12'],
            'tahun' => ['nullable', 'integer', 'between:2000,2100'],
            'status' => ['nullable', Rule::in([ClosingEvent::STATUS_ACTIVE, ClosingEvent::STATUS_CANCELLED])],
        ]);
        $month = (int) ($validated['bulan'] ?? $today->month);
        $year = (int) ($validated['tahun'] ?? $today->year);
        $period = CarbonImmutable::createFromDate($year, $month, 1, 'Asia/Jakarta');

        $status = $validated['status'] ?? null;
        $events = ClosingEvent::query()
            ->with(['pic:id,nama_pic', 'jenisEvent:id,jenis_event', 'lokasi:id,nama_lokasi'])
            ->overlapping($period->startOfMonth(), $period->endOfMonth())
            ->when($status, fn ($query, string $status) => $query->where('status_event', $status))
            ->orderBy('tanggal')
            ->orderBy('id')
            ->paginate(15)
            ->withQueryString()
            ->through(fn (ClosingEvent $event): array => $this->listPayload($event, $today));

        $bounds = ClosingEvent::query()
            ->selectRaw('MIN(tanggal) AS min_date, MAX(COALESCE(tanggal_selesai, tanggal)) AS max_date')
            ->first();
        $firstYear = $bounds?->min_date ? CarbonImmutable::parse($bounds->min_date)->year : $today->year;
        $lastYear = $bounds?->max_date ? CarbonImmutable::parse($bounds->max_date)->year : $today->year;
        $years = collect(range(min($firstYear, $today->year), max($lastYear, $today->year)));

        return Inertia::render('Internal/ClosingEvent/Index', [
            'events' => $events,
            'filters' => ['bulan' => $month, 'tahun' => $year, 'status' => $status],
            'years' => $years,
            'permissions' => $permissions,
            'user' => $this->userPayload($request),
        ]);
    }

    public function create(Request $request, ClosingEventAccess $access): Response
    {
        $permissions = $this->authorizeCapability($request, $access, 'canCreate');

        return Inertia::render('Internal/ClosingEvent/Create', [
            'masterData' => $this->masterData(),
            'permissions' => $permissions,
            'user' => $this->userPayload($request),
        ]);
    }

    public function store(StoreClosingEventRequest $request, CreateClosingEvent $action): RedirectResponse
    {
        $event = $action->handle($request->validated(), $request->user());

        return to_route('dashboard.closing-event.show', $event)->with('success', 'Closing Event berhasil ditambahkan.');
    }

    public function show(Request $request, ClosingEvent $closingEvent, ClosingEventAccess $access): Response
    {
        $permissions = $this->authorizeCapability($request, $access, 'canView');
        $closingEvent->load([
            'pic:id,nama_pic', 'jenisEvent:id,jenis_event', 'lokasi:id,nama_lokasi',
            'creator.karyawan:id,nama', 'updater.karyawan:id,nama', 'canceller.karyawan:id,nama',
        ]);

        return Inertia::render('Internal/ClosingEvent/Show', [
            'event' => $this->detailPayload($closingEvent),
            'permissions' => $permissions,
            'user' => $this->userPayload($request),
        ]);
    }

    public function edit(Request $request, ClosingEvent $closingEvent, ClosingEventAccess $access): Response
    {
        $permissions = $this->authorizeCapability($request, $access, 'canUpdate');
        $closingEvent->load('lokasi:id,nama_lokasi');

        return Inertia::render('Internal/ClosingEvent/Edit', [
            'event' => [
                'id' => $closingEvent->id,
                'pic_id' => $closingEvent->pic_id,
                'event_id' => $closingEvent->event_id,
                'tanggal' => $closingEvent->tanggal?->toDateString(),
                'tanggal_selesai' => $closingEvent->tanggal_selesai?->toDateString(),
                'status_event' => $closingEvent->status_event,
                'alasan_pembatalan' => $closingEvent->alasan_pembatalan,
                'konsumen' => $closingEvent->konsumen,
                'kontak' => $closingEvent->kontak,
                'jam_kedatangan' => substr((string) $closingEvent->jam_kedatangan, 0, 5),
                'lokasi_ids' => $closingEvent->lokasi->pluck('id')->all(),
                'additional' => $closingEvent->additional,
                'konsumsi' => $closingEvent->konsumsi,
                'jumlah_pengunjung' => $closingEvent->jumlah_pengunjung,
                'harga_total' => $closingEvent->harga_total,
                'panitia' => $closingEvent->panitia,
            ],
            'masterData' => $this->masterData(),
            'permissions' => $permissions,
            'user' => $this->userPayload($request),
        ]);
    }

    public function update(UpdateClosingEventRequest $request, ClosingEvent $closingEvent, UpdateClosingEvent $action): RedirectResponse
    {
        $action->handle($closingEvent, $request->validated(), $request->user());

        return to_route('dashboard.closing-event.show', $closingEvent)->with('success', 'Closing Event berhasil diperbarui.');
    }

    public function destroy(Request $request, ClosingEvent $closingEvent, ClosingEventAccess $access): RedirectResponse
    {
        $this->authorizeCapability($request, $access, 'canDelete');
        $closingEvent->delete();

        return to_route('dashboard.closing-event.index')->with('success', 'Closing Event berhasil dihapus.');
    }

    public function export(Request $request, ClosingEventAccess $access): BinaryFileResponse|RedirectResponse
    {
        $this->authorizeCapability($request, $access, 'canExport');
        $validated = $request->validate([
            'bulan' => ['required', 'integer', 'between:1,12'],
            'tahun' => ['required', 'integer', 'between:2000,2100'],
        ]);
        $period = CarbonImmutable::createFromDate(
            (int) $validated['tahun'],
            (int) $validated['bulan'],
            1,
            'Asia/Jakarta',
        );
        $events = ClosingEvent::query()
            ->with([
                'pic:id,nama_pic', 'jenisEvent:id,jenis_event', 'lokasi:id,nama_lokasi',
                'canceller.karyawan:id,nama',
            ])
            ->overlapping($period->startOfMonth(), $period->endOfMonth())
            ->orderBy('tanggal')
            ->orderBy('id')
            ->get();

        if ($events->isEmpty()) {
            return to_route('dashboard.closing-event.index', [
                'bulan' => $period->month,
                'tahun' => $period->year,
            ])->with('error', 'Tidak ada data Closing Event pada periode yang dipilih.');
        }

        $month = str($period->locale('id')->translatedFormat('F'))->lower()->slug();

        return Excel::download(
            new ClosingEventsExport($events),
            "closing-event-{$month}-{$period->year}.xlsx",
        );
    }

    private function authorizeCapability(Request $request, ClosingEventAccess $access, string $capability): array
    {
        $permissions = $access->for($request->user());
        abort_unless($permissions[$capability] ?? false, 403);

        return $permissions;
    }

    private function masterData(): array
    {
        return [
            'pic' => Pic::query()->orderBy('nama_pic')->get(['id', 'nama_pic']),
            'events' => JenisEvent::query()->orderBy('jenis_event')->get(['id', 'jenis_event']),
            'lokasi' => Lokasi::query()->orderBy('nama_lokasi')->get(['id', 'nama_lokasi']),
        ];
    }

    private function listPayload(ClosingEvent $event, ?CarbonImmutable $today = null): array
    {
        $today ??= CarbonImmutable::today('Asia/Jakarta');
        $start = CarbonImmutable::parse($event->tanggal->toDateString(), 'Asia/Jakarta')->startOfDay();
        $end = CarbonImmutable::parse(
            ($event->tanggal_selesai ?? $event->tanggal)->toDateString(),
            'Asia/Jakarta',
        )->startOfDay();

        return [
            'id' => $event->id,
            'tanggal' => $start->toDateString(),
            'tanggalSelesai' => $event->tanggal_selesai?->toDateString(),
            'tanggalLabel' => $this->dateRangeLabel($start, $event->tanggal_selesai ? $end : null),
            'tanggalMulaiLabel' => $start->locale('id')->translatedFormat('j F Y'),
            'tanggalSelesaiLabel' => $event->tanggal_selesai?->locale('id')->translatedFormat('j F Y'),
            'statusEvent' => $event->status_event,
            'statusLabel' => $event->status_event === ClosingEvent::STATUS_CANCELLED ? 'Dibatalkan' : 'Aktif',
            'isCancelled' => $event->status_event === ClosingEvent::STATUS_CANCELLED,
            'isOngoing' => $event->status_event === ClosingEvent::STATUS_ACTIVE
                && $start->lessThanOrEqualTo($today)
                && $end->greaterThanOrEqualTo($today),
            'konsumen' => $event->konsumen,
            'pic' => $event->pic?->nama_pic,
            'jenisEvent' => $event->jenisEvent?->jenis_event,
            'lokasi' => $event->lokasi->map(fn (Lokasi $lokasi): array => ['id' => $lokasi->id, 'name' => $lokasi->nama_lokasi]),
            'jamKedatangan' => substr((string) $event->jam_kedatangan, 0, 5),
            'jumlahPengunjung' => $event->jumlah_pengunjung,
            'konsumsi' => $event->konsumsi,
            'additional' => $event->additional,
            'panitia' => $event->panitia,
        ];
    }

    private function detailPayload(ClosingEvent $event): array
    {
        return [
            ...$this->listPayload($event),
            'kontak' => $event->kontak,
            'hargaTotal' => (float) $event->harga_total,
            'alasanPembatalan' => $event->alasan_pembatalan,
            'cancelledBy' => $event->canceller?->karyawan?->nama ?? $event->canceller?->username,
            'cancelledAt' => $event->cancelled_at?->locale('id')->translatedFormat('d F Y, H:i'),
            'hasCancellationHistory' => $event->cancelled_at !== null,
            'createdBy' => $event->creator?->karyawan?->nama ?? $event->creator?->username,
            'createdAt' => $event->created_at?->locale('id')->translatedFormat('d F Y, H:i'),
            'updatedBy' => $event->updater?->karyawan?->nama ?? $event->updater?->username,
            'updatedAt' => $event->updated_by ? $event->updated_at?->locale('id')->translatedFormat('d F Y, H:i') : null,
        ];
    }

    private function userPayload(Request $request): array
    {
        $user = $request->user()->loadMissing(['role:id,nama_role', 'karyawan:id,nama']);
        $name = $user->karyawan?->nama ?? $user->username;
        $roleName = mb_strtolower($user->role?->nama_role ?? '');

        return [
            'name' => $name,
            'initials' => collect(preg_split('/\s+/', trim($name)))->filter()->take(2)->map(fn ($part) => mb_strtoupper(mb_substr($part, 0, 1)))->implode(''),
            'roleName' => $roleName,
            'roleLabel' => str($roleName)->replace('_', ' ')->title()->toString(),
        ];
    }

    private function dateRangeLabel(CarbonImmutable $start, ?CarbonImmutable $end): string
    {
        $start = $start->locale('id');

        if (! $end || $start->isSameDay($end)) {
            return $start->day.' '.$this->shortMonth($start).' '.$start->year;
        }

        $end = $end->locale('id');

        if ($start->year === $end->year && $start->month === $end->month) {
            return $start->day.'–'.$end->day.' '.$this->shortMonth($end).' '.$end->year;
        }

        if ($start->year === $end->year) {
            return $start->day.' '.$this->shortMonth($start).'–'.$end->day.' '.$this->shortMonth($end).' '.$end->year;
        }

        return $start->day.' '.$this->shortMonth($start).' '.$start->year.'–'.$end->day.' '.$this->shortMonth($end).' '.$end->year;
    }

    private function shortMonth(CarbonImmutable $date): string
    {
        return [
            1 => 'Jan',
            2 => 'Feb',
            3 => 'Mar',
            4 => 'Apr',
            5 => 'Mei',
            6 => 'Jun',
            7 => 'Jul',
            8 => 'Agu',
            9 => 'Sep',
            10 => 'Okt',
            11 => 'Nov',
            12 => 'Des',
        ][$date->month];
    }
}
