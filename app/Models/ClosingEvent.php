<?php

namespace App\Models;

use Carbon\CarbonInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class ClosingEvent extends Model
{
    public const STATUS_ACTIVE = 'aktif';

    public const STATUS_CANCELLED = 'dibatalkan';

    protected $table = 'closing_event';

    protected $guarded = [];

    protected function casts(): array
    {
        return [
            'tanggal' => 'date',
            'tanggal_selesai' => 'date',
            'cancelled_at' => 'datetime',
            'konsumsi' => 'boolean',
            'harga_total' => 'decimal:2',
        ];
    }

    public function pic(): BelongsTo
    {
        return $this->belongsTo(Pic::class);
    }

    public function jenisEvent(): BelongsTo
    {
        return $this->belongsTo(JenisEvent::class, 'event_id');
    }

    public function lokasi(): BelongsToMany
    {
        return $this->belongsToMany(Lokasi::class, 'closing_event_lokasi');
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updater(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function canceller(): BelongsTo
    {
        return $this->belongsTo(User::class, 'cancelled_by');
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('status_event', self::STATUS_ACTIVE);
    }

    public function scopeOverlapping(
        Builder $query,
        CarbonInterface|string $periodStart,
        CarbonInterface|string $periodEnd,
    ): Builder {
        $start = $periodStart instanceof CarbonInterface ? $periodStart->toDateString() : $periodStart;
        $end = $periodEnd instanceof CarbonInterface ? $periodEnd->toDateString() : $periodEnd;

        return $query
            ->whereDate('tanggal', '<=', $end)
            ->where(function (Builder $query) use ($start): void {
                $query
                    ->whereDate('tanggal_selesai', '>=', $start)
                    ->orWhere(function (Builder $query) use ($start): void {
                        $query->whereNull('tanggal_selesai')->whereDate('tanggal', '>=', $start);
                    });
            });
    }
}
