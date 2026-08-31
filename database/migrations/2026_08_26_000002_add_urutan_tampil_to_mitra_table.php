<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('mitra', function (Blueprint $table): void {
            $table->unsignedInteger('urutan_tampil')->default(0)->after('is_active');
        });
    }

    public function down(): void
    {
        Schema::table('mitra', function (Blueprint $table): void {
            $table->dropColumn('urutan_tampil');
        });
    }
};
