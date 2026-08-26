<?php

namespace App\Actions\ClosingEvent;

use App\Models\ClosingEvent;
use App\Models\User;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

class UpdateClosingEvent
{
    public function handle(ClosingEvent $event, array $data, User $actor): ClosingEvent
    {
        if (($data['tanggal_selesai'] ?? null) === ($data['tanggal'] ?? null)) {
            $data['tanggal_selesai'] = null;
        }

        return DB::transaction(function () use ($event, $data, $actor): ClosingEvent {
            $wasCancelled = $event->status_event === ClosingEvent::STATUS_CANCELLED;
            $targetStatus = $data['status_event'] ?? $event->status_event;
            $willBeCancelled = $targetStatus === ClosingEvent::STATUS_CANCELLED;
            $changes = Arr::except($data, ['lokasi_ids']);

            if (! $wasCancelled && $willBeCancelled) {
                $changes['cancelled_at'] = now('Asia/Jakarta');
                $changes['cancelled_by'] = $actor->id;
            } elseif ($wasCancelled && $willBeCancelled && blank($changes['alasan_pembatalan'] ?? null)) {
                unset($changes['alasan_pembatalan']);
            } elseif (! $willBeCancelled) {
                unset($changes['alasan_pembatalan']);
            }

            $event->update([
                ...$changes,
                'updated_by' => $actor->id,
            ]);

            $event->lokasi()->sync($data['lokasi_ids']);

            return $event;
        });
    }
}
