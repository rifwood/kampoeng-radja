<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('karyawan', function (Blueprint $table): void {
            $table->dropForeign(['departemen_id']);
        });

        Schema::table('karyawan', function (Blueprint $table): void {
            $table->unsignedBigInteger('departemen_id')->nullable()->change();
            $table->foreign('departemen_id')->references('id')->on('departemen')->restrictOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('karyawan', function (Blueprint $table): void {
            $table->dropForeign(['departemen_id']);
        });

        Schema::table('karyawan', function (Blueprint $table): void {
            $table->unsignedBigInteger('departemen_id')->nullable(false)->change();
            $table->foreign('departemen_id')->references('id')->on('departemen')->restrictOnDelete();
        });
    }
};
