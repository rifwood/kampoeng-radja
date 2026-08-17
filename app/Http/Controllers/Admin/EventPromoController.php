<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreEventPromoRequest;
use App\Http\Requests\Admin\UpdateEventPromoRequest;
use App\Models\EventPromo;
use Illuminate\Http\RedirectResponse;
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

    public function store(StoreEventPromoRequest $request): RedirectResponse
    {
        $path = $request->file('poster')->store('event-promo', 'public');

        try {
            EventPromo::create([
                ...$request->safe()->only(['judul', 'deskripsi_singkat', 'link_wa']),
                'poster' => $path,
                'created_by' => $request->user()->id,
                'updated_by' => null,
            ]);
        } catch (Throwable $exception) {
            Storage::disk('public')->delete($path);

            throw $exception;
        }

        return to_route('admin.event-promo.index')
            ->with('success', 'Event & Promotion berhasil ditambahkan.');
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
                ...$request->safe()->only(['judul', 'deskripsi_singkat', 'link_wa']),
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

        return to_route('admin.event-promo.index')
            ->with('success', 'Event & Promotion berhasil diperbarui.');
    }

    public function destroy(EventPromo $eventPromo): RedirectResponse
    {
        $path = $eventPromo->poster;

        DB::transaction(fn () => $eventPromo->delete());
        $this->deletePosterIfUnused($path);

        return to_route('admin.event-promo.index')
            ->with('success', 'Event & Promotion berhasil dihapus.');
    }

    /**
     * @return array<string, int|string|null>
     */
    private function serialize(EventPromo $item): array
    {
        return [
            'id' => $item->id,
            'judul' => $item->judul,
            'deskripsi_singkat' => $item->deskripsi_singkat,
            'poster_url' => Storage::disk('public')->url($item->poster),
            'link_wa' => $item->link_wa,
        ];
    }

    private function deletePosterIfUnused(string $path): void
    {
        $isStillUsed = EventPromo::query()->where('poster', $path)->exists();

        if (! $isStillUsed) {
            Storage::disk('public')->delete($path);
        }
    }
}
