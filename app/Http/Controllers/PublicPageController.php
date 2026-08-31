<?php

namespace App\Http\Controllers;

use App\Models\EventPromo;
use App\Models\GaleriEvent;
use App\Models\HomeHero;
use App\Models\MediaBerita;
use App\Models\Mitra;
use App\Models\Produk;
use App\Models\Wahana;
use App\Support\WhatsAppNumber;
use Carbon\CarbonImmutable;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Inertia\Response;

class PublicPageController extends Controller
{
    public function home(): Response
    {
        $today = CarbonImmutable::now('Asia/Jakarta')->toDateString();

        return Inertia::render('Home', [
            'hero' => $this->formatHomeHero(HomeHero::query()->first()),
            'news' => MediaBerita::query()
                ->orderByDesc('tanggal_publish')
                ->orderByDesc('id')
                ->take(3)
                ->get()
                ->map(fn (MediaBerita $item): array => $this->formatMediaBerita($item)),
            'promotions' => EventPromo::query()
                ->where('is_active', true)
                ->whereDate('tanggal_mulai', '<=', $today)
                ->whereDate('tanggal_selesai', '>=', $today)
                ->orderBy('urutan_tampil')
                ->orderBy('id')
                ->get()
                ->map(fn (EventPromo $item): array => $this->formatEventPromo($item)),
            'promotionFallbackEnabled' => ! EventPromo::query()->exists(),
            'products' => Produk::query()
                ->where('is_active', true)
                ->orderBy('urutan_tampil')
                ->orderBy('id')
                ->get()
                ->map(fn (Produk $produk): array => [
                    'id' => $produk->id,
                    'name' => $produk->nama,
                    'description' => $produk->deskripsi_singkat,
                    'detail' => $produk->deskripsi_lengkap,
                    'thumbnail' => Storage::disk('public')->url($produk->thumbnail),
                    'heroImage' => Storage::disk('public')->url($produk->hero_image),
                ]),
            'partners' => Mitra::query()
                ->where('is_active', true)
                ->orderBy('urutan_tampil')
                ->orderBy('id')
                ->get()
                ->map(fn (Mitra $mitra): array => [
                    'id' => $mitra->id,
                    'name' => $mitra->nama_brand,
                    'logo' => Storage::disk('public')->url($mitra->logo),
                ]),
            'featuredRides' => Wahana::query()
                ->with('coverFoto')
                ->where('is_active', true)
                ->where('is_unggulan', true)
                ->orderBy('urutan_tampil')
                ->orderBy('id')
                ->take(3)
                ->get()
                ->map(fn (Wahana $wahana) => $this->formatFeaturedWahana($wahana)),
            'featuredRideFallbackEnabled' => ! Wahana::query()->exists(),
        ]);
    }

    /**
     * @return array<string, int|string|null>|null
     */
    private function formatHomeHero(?HomeHero $hero): ?array
    {
        if (! $hero) {
            return null;
        }

        return [
            'id' => $hero->id,
            'video_url' => $hero->video_path ? Storage::disk('public')->url($hero->video_path) : null,
            'poster_url' => $hero->poster_path ? Storage::disk('public')->url($hero->poster_path) : null,
        ];
    }

    public function about(): Response
    {
        return Inertia::render('TentangKami');
    }

    public function news(): Response
    {
        return Inertia::render('Berita', [
            'articles' => MediaBerita::query()
                ->orderByDesc('tanggal_publish')
                ->orderByDesc('id')
                ->get()
                ->map(fn (MediaBerita $item): array => $this->formatMediaBerita($item)),
        ]);
    }

    public function rides(): Response
    {
        return Inertia::render('Wahana', [
            'categories' => $this->wahanaCategories(),
            'photos' => Wahana::query()
                ->with(['fotos' => fn ($query) => $query->orderBy('urutan')->orderBy('id')])
                ->where('is_active', true)
                ->orderBy('urutan_tampil')
                ->orderBy('id')
                ->get()
                ->map(fn (Wahana $wahana) => $this->formatWahana($wahana)),
            'wahanaFallbackEnabled' => ! Wahana::query()->exists(),
        ]);
    }

    public function events(): Response
    {
        return Inertia::render('GaleriEvent', [
            'events' => GaleriEvent::query()
                ->with(['photos' => fn ($query) => $query
                    ->orderByRaw('CASE WHEN urutan IS NULL THEN 1 ELSE 0 END')
                    ->orderBy('urutan')
                    ->orderBy('id')])
                ->orderByDesc('tanggal_event')
                ->orderByDesc('id')
                ->get()
                ->map(fn (GaleriEvent $event) => [
                    'id' => $event->id,
                    'nama_event' => $event->nama_event,
                    'tanggal_event' => $event->tanggal_event?->toDateString(),
                    'deskripsi' => $event->deskripsi,
                    // Aliases dipertahankan sementara agar tampilan publik existing tetap kompatibel.
                    'title' => $event->nama_event,
                    'description' => $event->deskripsi,
                    'event_date' => $event->tanggal_event?->toDateString(),
                    'photos' => $event->photos->map(fn ($photo) => [
                        'id' => $photo->id,
                        'url' => Storage::disk('public')->url($photo->foto),
                        'caption' => $photo->caption,
                        'urutan' => $photo->urutan,
                    ])->values(),
                ]),
        ]);
    }

    private function formatWahana(Wahana $wahana): array
    {
        $labels = collect($wahana->labels())
            ->map(fn (string $label, int $index) => [
                'id' => $wahana->id.'-'.$index,
                'name' => $label,
                'slug' => str($label)->slug()->toString(),
            ]);

        $photos = $wahana->fotos->isNotEmpty()
            ? $wahana->fotos->map(fn ($photo): array => [
                'id' => $photo->id,
                'url' => Storage::disk('public')->url($photo->foto),
            ])->values()
            : collect([[
                'id' => 'legacy-'.$wahana->id,
                'url' => Storage::disk('public')->url($wahana->foto),
            ]]);

        return [
            'id' => $wahana->id,
            'title' => $wahana->nama_wahana,
            'description' => $wahana->deskripsi_singkat,
            'cover_url' => $photos->first()['url'],
            'photos' => $photos,
            'alt_text' => $wahana->nama_wahana,
            'labels' => $labels,
        ];
    }

    private function formatFeaturedWahana(Wahana $wahana): array
    {
        $labels = collect($wahana->labels())
            ->map(fn (string $label, int $index): array => [
                'id' => $wahana->id.'-'.$index,
                'name' => $label,
                'slug' => str($label)->slug()->toString(),
            ]);
        $coverPath = $wahana->coverFoto?->foto ?: $wahana->foto;

        return [
            'id' => $wahana->id,
            'title' => $wahana->nama_wahana,
            'description' => $wahana->deskripsi_singkat,
            'cover_url' => $coverPath ? Storage::disk('public')->url($coverPath) : null,
            'alt_text' => $wahana->nama_wahana,
            'labels' => $labels,
        ];
    }

    /**
     * @return array<string, int|string|null>
     */
    private function formatMediaBerita(MediaBerita $item): array
    {
        return [
            'id' => $item->id,
            'title' => $item->judul,
            'description' => $item->deskripsi,
            'foto_url' => Storage::disk('public')->url($item->foto),
            'tanggal_publish' => $item->tanggal_publish?->toIso8601String(),
        ];
    }

    /**
     * @return array<string, int|string|null>
     */
    private function formatEventPromo(EventPromo $item): array
    {
        return [
            'id' => $item->id,
            'title' => $item->judul,
            'description' => $item->deskripsi_singkat,
            'detail' => $item->deskripsi_lengkap,
            'period' => $item->periodLabel(),
            'tanggal_mulai' => $item->tanggal_mulai?->toDateString(),
            'tanggal_selesai' => $item->tanggal_selesai?->toDateString(),
            'poster_url' => Storage::disk('public')->url($item->poster),
            'link_wa' => app(WhatsAppNumber::class)->toUrl($item->link_wa),
        ];
    }

    private function wahanaCategories(): array
    {
        $labels = Wahana::query()->where('is_active', true)->whereNotNull('label')->pluck('label')
            ->flatMap(fn (string $labels) => explode(',', $labels))
            ->map(fn (string $label) => trim($label))
            ->filter()
            ->unique(fn (string $label) => str($label)->lower()->toString())
            ->values()
            ->map(fn (string $label, int $index) => [
                'id' => $index + 1,
                'name' => $label,
                'slug' => str($label)->slug()->toString(),
            ]);

        return [['id' => 1, 'name' => 'Wahana', 'labels' => $labels]];
    }
}
