<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('event_promo', function (Blueprint $table): void {
            $table->text('deskripsi_lengkap')->nullable()->after('deskripsi_singkat');
            $table->date('tanggal_mulai')->nullable()->after('poster');
            $table->date('tanggal_selesai')->nullable()->after('tanggal_mulai');
            $table->boolean('is_active')->default(true)->after('link_wa');
            $table->unsignedInteger('urutan_tampil')->default(0)->after('is_active');
        });
    }

    public function down(): void
    {
        Schema::table('event_promo', function (Blueprint $table): void {
            $table->dropColumn([
                'deskripsi_lengkap',
                'tanggal_mulai',
                'tanggal_selesai',
                'is_active',
                'urutan_tampil',
            ]);
        });
    }
};
