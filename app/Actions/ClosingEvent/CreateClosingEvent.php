<?php

namespace App\Actions\ClosingEvent;

use App\Models\ClosingEvent;
use App\Models\User;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

class CreateClosingEvent
{
    public function handle(array $data, User $actor): ClosingEvent
    {
        if (($data['tanggal_selesai'] ?? null) === ($data['tanggal'] ?? null)) {
            $data['tanggal_selesai'] = null;
        }

        return DB::transaction(function () use ($data, $actor): ClosingEvent {
            $event = ClosingEvent::create([
                ...Arr::except($data, ['lokasi_ids']),
                'status_event' => ClosingEvent::STATUS_ACTIVE,
                'created_by' => $actor->id,
                'updated_by' => null,
            ]);

            $event->lokasi()->attach($data['lokasi_ids']);

            return $event;
        });
    }
}
