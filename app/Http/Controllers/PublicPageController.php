<?php

namespace App\Http\Controllers;

use App\Models\EventPromo;
use App\Models\GaleriEvent;
use App\Models\MediaBerita;
use App\Models\Mitra;
use App\Models\Wahana;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Inertia\Response;

class PublicPageController extends Controller
{
    public function home(): Response
    {
        return Inertia::render('Home', [
            'news' => MediaBerita::query()
                ->latest('tanggal_publish')
                ->take(3)
                ->get()
                ->map(fn (MediaBerita $item): array => $this->formatMediaBerita($item)),
            'promotions' => EventPromo::query()
                ->latest()
                ->take(3)
                ->get()
                ->map(fn (EventPromo $item): array => $this->formatEventPromo($item)),
            'partners' => Mitra::query()->where('is_active', true)->orderBy('nama_brand')->get()
                ->map(fn (Mitra $mitra) => ['id' => $mitra->id, 'name' => $mitra->nama_brand, 'logo' => $mitra->logo]),
            'featuredRides' => Wahana::query()
                ->where('is_unggulan', true)
                ->take(3)
                ->get()
                ->map(fn (Wahana $wahana) => $this->formatWahana($wahana)),
        ]);
    }

    public function about(): Response
    {
        return Inertia::render('TentangKami');
    }

    public function news(): Response
    {
        return Inertia::render('Berita', [
            'articles' => MediaBerita::query()
                ->latest('tanggal_publish')
                ->get()
                ->map(fn (MediaBerita $item): array => $this->formatMediaBerita($item)),
        ]);
    }

    public function rides(): Response
    {
        return Inertia::render('Wahana', [
            'categories' => $this->wahanaCategories(),
            'photos' => Wahana::query()->latest()->get()->map(fn (Wahana $wahana) => $this->formatWahana($wahana)),
        ]);
    }

    public function events(): Response
    {
        return Inertia::render('GaleriEvent', [
            'events' => GaleriEvent::query()->with('photos')->latest('tanggal_event')->get()
                ->map(fn (GaleriEvent $event) => [
                    'id' => $event->id,
                    'title' => $event->nama_event,
                    'description' => $event->deskripsi,
                    'event_date' => $event->tanggal_event?->toDateString(),
                    'photos' => $event->photos->map(fn ($photo) => [
                        'id' => $photo->id,
                        'photo_path' => $photo->foto,
                        'alt_text' => $photo->caption,
                    ]),
                ]),
        ]);
    }

    private function formatWahana(Wahana $wahana): array
    {
        $labels = collect(explode(',', (string) $wahana->label))
            ->map(fn (string $label) => trim($label))
            ->filter()
            ->values()
            ->map(fn (string $label, int $index) => [
                'id' => $wahana->id.'-'.$index,
                'name' => $label,
                'slug' => str($label)->slug()->toString(),
            ]);

        return [
            'id' => $wahana->id,
            'title' => $wahana->nama_wahana,
            'description' => $wahana->deskripsi_singkat,
            'photo_path' => $wahana->foto,
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
            'poster_url' => Storage::disk('public')->url($item->poster),
            'link_wa' => $item->link_wa,
        ];
    }

    private function wahanaCategories(): array
    {
        $labels = Wahana::query()->whereNotNull('label')->pluck('label')
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
