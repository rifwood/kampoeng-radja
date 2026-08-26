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
        $oldPosterPath = $hero->poster_path;
        $newVideoPath = null;
        $newPosterPath = null;

        try {
            $newVideoPath = $request->file('video')?->store('home/hero', 'public');
            $newPosterPath = $request->file('poster')?->store('home/hero', 'public');

            DB::transaction(function () use ($request, $hero, $newVideoPath, $newPosterPath): void {
                $hero->fill([
                    ...$request->safe()->only([
                        'eyebrow',
                        'judul',
                        'tagline',
                        'deskripsi',
                        'cta_primary_label',
                        'cta_primary_url',
                        'cta_secondary_label',
                        'cta_secondary_url',
                    ]),
                    'video_path' => $newVideoPath ?? $hero->video_path,
                    'poster_path' => $newPosterPath ?? $hero->poster_path,
                    'updated_by' => $request->user()->id,
                ])->save();
            });
        } catch (Throwable $exception) {
            Storage::disk('public')->delete(array_values(array_filter([$newVideoPath, $newPosterPath])));

            throw $exception;
        }

        if ($newVideoPath && $oldVideoPath) {
            $this->deleteAssetIfUnused($oldVideoPath, 'video_path');
        }

        if ($newPosterPath && $oldPosterPath) {
            $this->deleteAssetIfUnused($oldPosterPath, 'poster_path');
        }

        return to_route('dashboard.cms.home')
            ->with('success', 'Hero Beranda berhasil diperbarui.');
    }

    private function deleteAssetIfUnused(string $path, string $column): void
    {
        if (! HomeHero::query()->where($column, $path)->exists()) {
            Storage::disk('public')->delete($path);
        }
    }
}
