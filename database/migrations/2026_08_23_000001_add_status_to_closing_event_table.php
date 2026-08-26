<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('closing_event', function (Blueprint $table): void {
            $table->enum('status_event', ['aktif', 'dibatalkan'])
                ->default('aktif')
                ->after('tanggal_selesai')
                ->index();
            $table->text('alasan_pembatalan')->nullable()->after('panitia');
            $table->timestamp('cancelled_at')->nullable()->after('alasan_pembatalan');
            $table->foreignId('cancelled_by')
                ->nullable()
                ->after('cancelled_at')
                ->constrained('users')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('closing_event', function (Blueprint $table): void {
            $table->dropConstrainedForeignId('cancelled_by');
            $table->dropColumn(['status_event', 'alasan_pembatalan', 'cancelled_at']);
        });
    }
};
