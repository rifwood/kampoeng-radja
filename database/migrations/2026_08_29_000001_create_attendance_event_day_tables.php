<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('attendance_days', function (Blueprint $table): void {
            $table->id();
            $table->date('tanggal')->unique();
            $table->enum('tipe_hari', ['normal', 'event'])->default('normal');
            $table->string('nama_event')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });

        Schema::create('event_attendance_schedules', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('attendance_day_id')->constrained('attendance_days')->cascadeOnDelete();
            $table->time('jam_masuk');
            $table->unsignedTinyInteger('toleransi_menit')->default(5);
            $table->unsignedInteger('urutan')->default(0);
            $table->timestamps();
        });

        Schema::create('event_attendance_members', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('attendance_day_id')->constrained('attendance_days')->cascadeOnDelete();
            $table->foreignId('event_attendance_schedule_id')->constrained('event_attendance_schedules')->cascadeOnDelete();
            $table->foreignId('karyawan_id')->constrained('karyawan')->restrictOnDelete();
            $table->timestamps();

            $table->unique(['attendance_day_id', 'karyawan_id'], 'event_member_unique_per_day');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('event_attendance_members');
        Schema::dropIfExists('event_attendance_schedules');
        Schema::dropIfExists('attendance_days');
    }
};
