<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('produk', function (Blueprint $table): void {
            $table->id();
            $table->string('nama', 150);
            $table->string('deskripsi_singkat', 250);
            $table->text('deskripsi_lengkap')->nullable();
            $table->string('thumbnail', 255);
            $table->string('hero_image', 255);
            $table->boolean('is_active')->default(true);
            $table->unsignedInteger('urutan_tampil')->default(0);
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('produk');
    }
};
