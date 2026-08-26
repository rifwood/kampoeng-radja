<?php

namespace App\Http\Requests\ClosingEvent;

trait ClosingEventRules
{
    /** @return array<string, array<int, mixed>> */
    protected function closingEventRules(): array
    {
        return [
            'pic_id' => ['required', 'integer', 'exists:pic,id'],
            'event_id' => ['required', 'integer', 'exists:event,id'],
            'tanggal' => ['required', 'date'],
            'tanggal_selesai' => ['nullable', 'date', 'after_or_equal:tanggal'],
            'konsumen' => ['required', 'string', 'max:150'],
            'kontak' => ['required', 'string', 'max:20'],
            'jam_kedatangan' => ['required', 'date_format:H:i'],
            'lokasi_ids' => ['required', 'array', 'min:1'],
            'lokasi_ids.*' => ['required', 'integer', 'distinct', 'exists:lokasi,id'],
            'additional' => ['nullable', 'string'],
            'konsumsi' => ['required', 'boolean'],
            'jumlah_pengunjung' => ['required', 'integer', 'min:1'],
            'harga_total' => ['required', 'numeric', 'min:0'],
            'panitia' => ['nullable', 'string'],
        ];
    }
}
