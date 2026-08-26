<?php

namespace App\Models;

use Carbon\CarbonImmutable;
use Carbon\CarbonInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EventPromo extends Model
{
    protected $table = 'event_promo';

    protected $fillable = [
        'created_by',
        'updated_by',
        'judul',
        'deskripsi_singkat',
        'deskripsi_lengkap',
        'poster',
        'tanggal_mulai',
        'tanggal_selesai',
        'link_wa',
        'is_active',
        'urutan_tampil',
    ];

    protected function casts(): array
    {
        return [
            'tanggal_mulai' => 'date',
            'tanggal_selesai' => 'date',
            'is_active' => 'boolean',
            'urutan_tampil' => 'integer',
        ];
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updater(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function displayStatus(?CarbonInterface $today = null): string
    {
        $today ??= CarbonImmutable::now('Asia/Jakarta')->startOfDay();

        if (! $this->is_active) {
            return 'nonaktif';
        }

        if (! $this->tanggal_mulai || ! $this->tanggal_selesai) {
            return 'nonaktif';
        }

        $todayDate = $today->toDateString();

        if ($this->tanggal_mulai->toDateString() > $todayDate) {
            return 'akan_datang';
        }

        if ($this->tanggal_selesai->toDateString() < $todayDate) {
            return 'berakhir';
        }

        return 'aktif';
    }

    public function periodLabel(): string
    {
        if (! $this->tanggal_mulai || ! $this->tanggal_selesai) {
            return 'Periode belum dilengkapi';
        }

        $start = $this->tanggal_mulai;
        $end = $this->tanggal_selesai;

        if ($start->isSameDay($end)) {
            return sprintf('%d %s %d', $start->day, $this->monthName($start->month), $start->year);
        }

        if ($start->year === $end->year && $start->month === $end->month) {
            return sprintf('%d–%d %s %d', $start->day, $end->day, $this->monthName($end->month), $end->year);
        }

        if ($start->year === $end->year) {
            return sprintf(
                '%d %s–%d %s %d',
                $start->day,
                $this->monthName($start->month),
                $end->day,
                $this->monthName($end->month),
                $end->year,
            );
        }

        return sprintf(
            '%d %s %d–%d %s %d',
            $start->day,
            $this->monthName($start->month),
            $start->year,
            $end->day,
            $this->monthName($end->month),
            $end->year,
        );
    }

    private function monthName(int $month): string
    {
        return [
            1 => 'Januari',
            2 => 'Februari',
            3 => 'Maret',
            4 => 'April',
            5 => 'Mei',
            6 => 'Juni',
            7 => 'Juli',
            8 => 'Agustus',
            9 => 'September',
            10 => 'Oktober',
            11 => 'November',
            12 => 'Desember',
        ][$month];
    }
}
