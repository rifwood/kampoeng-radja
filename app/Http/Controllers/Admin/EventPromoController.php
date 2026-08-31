<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreEventPromoRequest;
use App\Http\Requests\Admin\UpdateEventPromoRequest;
use App\Models\EventPromo;
use App\Models\HomeHero;
use App\Models\MediaBerita;
use App\Models\Mitra;
use App\Models\Produk;
use App\Support\WhatsAppNumber;
use Carbon\CarbonImmutable;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Inertia\Response;
use Throwable;

class EventPromoController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('Admin/EventPromo/Index', [
            'items' => EventPromo::query()
                ->latest()
                ->get()
                ->map(fn (EventPromo $item): array => $this->serialize($item)),
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('Admin/EventPromo/Create');
    }

    public function home(Request $request): Response
    {
        $items = EventPromo::query()
            ->orderBy('urutan_tampil')
            ->orderBy('id')
            ->get()
            ->map(fn (EventPromo $item): array => $this->serialize($item));

        $name = $request->user()->karyawan()->value('nama') ?? $request->user()->username;
        $roleName = $request->user()->role()->value('nama_role') ?? 'user';

        return Inertia::render('Internal/CMS/Home/Index', [
            'hero' => $this->serializeHero(HomeHero::query()->first()),
            'newsItems' => MediaBerita::query()
                ->orderByDesc('tanggal_publish')
                ->orderByDesc('id')
                ->get()
                ->map(fn (MediaBerita $item): array => [
                    'id' => $item->id,
                    'judul' => $item->judul,
                    'deskripsi' => $item->deskripsi,
                    'foto_url' => Storage::disk('public')->url($item->foto),
                    'tanggal_publish' => $item->tanggal_publish?->format('Y-m-d\TH:i'),
                    'tanggal_publish_iso' => $item->tanggal_publish?->toIso8601String(),
                ]),
            'promotions' => $items,
            'promoSummary' => [
                'active_count' => $items->where('status', 'aktif')->count(),
                'total_count' => $items->count(),
            ],
            'products' => Produk::query()
                ->orderBy('urutan_tampil')
                ->orderBy('id')
                ->get()
                ->map(fn (Produk $produk): array => [
                    'id' => $produk->id,
                    'nama' => $produk->nama,
                    'deskripsi_singkat' => $produk->deskripsi_singkat,
                    'deskripsi_lengkap' => $produk->deskripsi_lengkap,
                    'thumbnail_url' => Storage::disk('public')->url($produk->thumbnail),
                    'hero_image_url' => Storage::disk('public')->url($produk->hero_image),
                    'is_active' => $produk->is_active,
                    'urutan_tampil' => $produk->urutan_tampil,
                ]),
            'partners' => Mitra::query()
                ->orderBy('urutan_tampil')
                ->orderBy('id')
                ->get()
                ->map(fn (Mitra $mitra): array => [
                    'id' => $mitra->id,
                    'nama_brand' => $mitra->nama_brand,
                    'logo_url' => Storage::disk('public')->url($mitra->logo),
                    'is_active' => $mitra->is_active,
                    'urutan_tampil' => $mitra->urutan_tampil,
                ]),
            'user' => [
                'name' => $name,
                'initials' => collect(preg_split('/\s+/', trim($name)))
                    ->filter()
                    ->take(2)
                    ->map(fn (string $part): string => mb_strtoupper(mb_substr($part, 0, 1)))
                    ->implode(''),
                'roleName' => $roleName,
                'roleLabel' => str($roleName)->replace('_', ' ')->title()->toString(),
            ],
        ]);
    }

    /**
     * @return array<string, int|string|null>|null
     */
    private function serializeHero(?HomeHero $hero): ?array
    {
        if (! $hero) {
            return null;
        }

        return [
            'id' => $hero->id,
            'video_url' => $hero->video_path ? Storage::disk('public')->url($hero->video_path) : null,
        ];
    }

    public function store(StoreEventPromoRequest $request): RedirectResponse
    {
        $path = $request->file('poster')->store('event-promo', 'public');

        try {
            EventPromo::create([
                ...$this->validatedContent($request),
                'poster' => $path,
                'created_by' => $request->user()->id,
                'updated_by' => null,
            ]);
        } catch (Throwable $exception) {
            Storage::disk('public')->delete($path);

            throw $exception;
        }

        return $this->redirectAfterMutation($request)
            ->with('success', 'Promo berhasil ditambahkan.');
    }

    public function edit(EventPromo $eventPromo): Response
    {
        return Inertia::render('Admin/EventPromo/Edit', [
            'item' => $this->serialize($eventPromo),
        ]);
    }

    public function update(UpdateEventPromoRequest $request, EventPromo $eventPromo): RedirectResponse
    {
        $oldPath = $eventPromo->poster;
        $newPath = $request->file('poster')?->store('event-promo', 'public');

        try {
            $eventPromo->update([
                ...$this->validatedContent($request),
                'poster' => $newPath ?? $oldPath,
                'updated_by' => $request->user()->id,
            ]);
        } catch (Throwable $exception) {
            if ($newPath) {
                Storage::disk('public')->delete($newPath);
            }

            throw $exception;
        }

        if ($newPath) {
            $this->deletePosterIfUnused($oldPath);
        }

        return $this->redirectAfterMutation($request)
            ->with('success', 'Promo berhasil diperbarui.');
    }

    public function toggleStatus(Request $request, EventPromo $eventPromo): RedirectResponse
    {
        $eventPromo->update([
            'is_active' => ! $eventPromo->is_active,
            'updated_by' => $request->user()->id,
        ]);

        return $this->redirectAfterMutation($request)
            ->with('success', $eventPromo->is_active ? 'Promo berhasil diaktifkan.' : 'Promo berhasil dinonaktifkan.');
    }

    public function destroy(Request $request, EventPromo $eventPromo): RedirectResponse
    {
        $path = $eventPromo->poster;

        DB::transaction(fn () => $eventPromo->delete());
        $this->deletePosterIfUnused($path);

        return $this->redirectAfterMutation($request)
            ->with('success', 'Promo berhasil dihapus.');
    }

    /**
     * @return array<string, bool|int|string|null>
     */
    private function serialize(EventPromo $item): array
    {
        return [
            'id' => $item->id,
            'judul' => $item->judul,
            'deskripsi_singkat' => $item->deskripsi_singkat,
            'deskripsi_lengkap' => $item->deskripsi_lengkap,
            'poster_url' => Storage::disk('public')->url($item->poster),
            'tanggal_mulai' => $item->tanggal_mulai?->toDateString(),
            'tanggal_selesai' => $item->tanggal_selesai?->toDateString(),
            'periode' => $item->periodLabel(),
            'link_wa' => app(WhatsAppNumber::class)->toLocalInput($item->link_wa),
            'link_wa_url' => app(WhatsAppNumber::class)->toUrl($item->link_wa),
            'is_active' => $item->is_active,
            'urutan_tampil' => $item->urutan_tampil,
            'status' => $item->displayStatus(CarbonImmutable::now('Asia/Jakarta')->startOfDay()),
        ];
    }

    /**
     * @return array<string, bool|int|string|null>
     */
    private function validatedContent(StoreEventPromoRequest|UpdateEventPromoRequest $request): array
    {
        return [
            ...$request->safe()->only([
                'judul',
                'deskripsi_singkat',
                'deskripsi_lengkap',
                'tanggal_mulai',
                'tanggal_selesai',
                'link_wa',
                'urutan_tampil',
            ]),
            'is_active' => $request->boolean('is_active'),
        ];
    }

    private function redirectAfterMutation(Request $request): RedirectResponse
    {
        if ($request->routeIs('dashboard.cms.*')) {
            return to_route('dashboard.cms.home');
        }

        return to_route('admin.event-promo.index');
    }

    private function deletePosterIfUnused(string $path): void
    {
        $isStillUsed = EventPromo::query()->where('poster', $path)->exists();

        if (! $isStillUsed) {
            Storage::disk('public')->delete($path);
        }
    }
}
