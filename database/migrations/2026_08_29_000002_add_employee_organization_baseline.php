<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('penempatan', function (Blueprint $table): void {
            $table->id();
            $table->string('nama_penempatan', 100)->unique();
            $table->timestamps();
        });

        Schema::table('karyawan', function (Blueprint $table): void {
            $table->foreignId('penempatan_id')
                ->nullable()
                ->after('departemen_id')
                ->constrained('penempatan')
                ->nullOnDelete();
            $table->foreignId('atasan_langsung_id')
                ->nullable()
                ->after('penempatan_id')
                ->constrained('karyawan')
                ->nullOnDelete();
            $table->string('foto_tanda_tangan', 255)->nullable()->after('foto_ktp');
        });
    }

    public function down(): void
    {
        Schema::table('karyawan', function (Blueprint $table): void {
            $table->dropForeign(['atasan_langsung_id']);
            $table->dropForeign(['penempatan_id']);
            $table->dropColumn(['atasan_langsung_id', 'penempatan_id', 'foto_tanda_tangan']);
        });

        Schema::dropIfExists('penempatan');
    }
};
