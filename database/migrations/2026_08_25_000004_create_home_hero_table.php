<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('home_hero', function (Blueprint $table): void {
            $table->id();
            $table->string('video_path')->nullable();
            $table->string('poster_path')->nullable();
            $table->string('eyebrow', 100)->nullable();
            $table->string('judul', 150);
            $table->string('tagline', 255)->nullable();
            $table->text('deskripsi')->nullable();
            $table->string('cta_primary_label', 100)->nullable();
            $table->string('cta_primary_url', 2048)->nullable();
            $table->string('cta_secondary_label', 100)->nullable();
            $table->string('cta_secondary_url', 2048)->nullable();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('home_hero');
    }
};
