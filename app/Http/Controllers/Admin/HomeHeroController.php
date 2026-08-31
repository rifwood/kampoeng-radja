<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UpdateHomeHeroRequest;
use App\Models\HomeHero;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Throwable;

class HomeHeroController extends Controller
{
    public function update(UpdateHomeHeroRequest $request): RedirectResponse
    {
        $hero = HomeHero::query()->firstOrNew(['id' => 1]);
        $oldVideoPath = $hero->video_path;
        $newVideoPath = null;

        try {
            $newVideoPath = $request->file('video')?->store('home/hero', 'public');

            DB::transaction(function () use ($request, $hero, $newVideoPath): void {
                if (! $hero->exists) {
                    // Required by the legacy schema, but no longer exposed by the video-only Hero.
                    $hero->judul = 'Kampoeng Radja';
                }

                $hero->fill([
                    'video_path' => $newVideoPath ?? $hero->video_path,
                    'updated_by' => $request->user()->id,
                ])->save();
            });
        } catch (Throwable $exception) {
            if ($newVideoPath) {
                Storage::disk('public')->delete($newVideoPath);
            }

            throw $exception;
        }

        if ($newVideoPath && $oldVideoPath) {
            $this->deleteAssetIfUnused($oldVideoPath);
        }

        return to_route('dashboard.cms.home')
            ->with('success', 'Hero Beranda berhasil diperbarui.');
    }

    private function deleteAssetIfUnused(string $path): void
    {
        if (! HomeHero::query()->where('video_path', $path)->exists()) {
            Storage::disk('public')->delete($path);
        }
    }
}
