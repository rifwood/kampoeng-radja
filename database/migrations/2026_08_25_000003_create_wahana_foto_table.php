<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('wahana_foto', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('wahana_id')->constrained('wahana')->cascadeOnDelete();
            $table->string('foto', 255);
            $table->unsignedInteger('urutan')->default(0);
            $table->timestamps();

            $table->index(['wahana_id', 'urutan']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('wahana_foto');
    }
};
