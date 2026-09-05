<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tempat_makan', function (Blueprint $table): void {
            $table->id();
            $table->string('nama', 150);
            $table->string('kategori', 20);
            $table->string('tagline', 200)->nullable();
            $table->text('deskripsi');
            $table->time('jam_buka')->nullable();
            $table->time('jam_tutup')->nullable();
            $table->unsignedInteger('kapasitas')->nullable();
            $table->string('lokasi', 150)->nullable();
            $table->string('jenis_menu', 150)->nullable();
            $table->boolean('is_recommended')->default(false);
            $table->boolean('is_active')->default(true);
            $table->unsignedInteger('urutan_tampil')->default(0);
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            $table->index(['is_active', 'urutan_tampil']);
            $table->index('kategori');
        });

        Schema::create('tempat_makan_foto', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('tempat_makan_id')->constrained('tempat_makan')->cascadeOnDelete();
            $table->string('foto', 255);
            $table->unsignedInteger('urutan')->default(0);
            $table->timestamps();

            $table->index(['tempat_makan_id', 'urutan']);
        });

        Schema::create('tempat_makan_menu_highlight', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('tempat_makan_id')->constrained('tempat_makan')->cascadeOnDelete();
            $table->string('nama_menu', 100);
            $table->unsignedInteger('urutan')->default(0);
            $table->timestamps();

            $table->index(['tempat_makan_id', 'urutan']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tempat_makan_menu_highlight');
        Schema::dropIfExists('tempat_makan_foto');
        Schema::dropIfExists('tempat_makan');
    }
};
