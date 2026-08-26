<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('closing_event', function (Blueprint $table): void {
            $table->date('tanggal_selesai')->nullable()->after('tanggal')->index();
        });
    }

    public function down(): void
    {
        Schema::table('closing_event', function (Blueprint $table): void {
            $table->dropIndex(['tanggal_selesai']);
            $table->dropColumn('tanggal_selesai');
        });
    }
};
