<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('departemen', function (Blueprint $table) {
            $table->id();
            $table->string('nama_departemen', 100)->unique();
        });

        Schema::create('jabatan', function (Blueprint $table) {
            $table->id();
            $table->string('nama_jabatan', 100)->unique();
        });

        Schema::create('role', function (Blueprint $table) {
            $table->id();
            $table->string('nama_role', 20)->unique();
        });

        Schema::create('karyawan', function (Blueprint $table) {
            $table->id();
            $table->string('nik', 20)->unique();
            $table->string('nama', 100);
            $table->date('tanggal_lahir');
            $table->string('tempat_lahir', 100);
            $table->enum('jenis_kelamin', ['L', 'P']);
            $table->text('alamat');
            $table->enum('agama', ['islam', 'kristen', 'katolik', 'hindu', 'buddha', 'konghucu']);
            $table->enum('status_perkawinan', ['belum kawin', 'kawin', 'cerai hidup', 'cerai mati']);
            $table->enum('pendidikan', ['SD', 'SMP', 'SMA', 'MAN', 'SMK', 'D3', 'D4', 'S1', 'S2', 'S3']);
            $table->foreignId('jabatan_id')->constrained('jabatan')->restrictOnDelete();
            $table->foreignId('departemen_id')->constrained('departemen')->restrictOnDelete();
            $table->enum('status_keaktifan', ['aktif', 'nonaktif']);
            $table->enum('status_kerja', ['kontrak', 'magang', 'buruh', 'freelance']);
            $table->date('tanggal_masuk');
            $table->date('tanggal_keluar')->nullable();
            $table->string('no_hp', 20);
            $table->string('foto_ktp', 255)->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('karyawan');
        Schema::dropIfExists('role');
        Schema::dropIfExists('jabatan');
        Schema::dropIfExists('departemen');
    }
};
