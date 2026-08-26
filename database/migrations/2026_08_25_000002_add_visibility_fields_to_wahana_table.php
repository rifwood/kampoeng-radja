<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('wahana', function (Blueprint $table): void {
            $table->boolean('is_active')->default(true)->after('is_unggulan');
            $table->unsignedInteger('urutan_tampil')->default(0)->after('is_active');
        });
    }

    public function down(): void
    {
        Schema::table('wahana', function (Blueprint $table): void {
            $table->dropColumn(['is_active', 'urutan_tampil']);
        });
    }
};
