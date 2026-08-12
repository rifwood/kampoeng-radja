<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('categories', function (Blueprint $table) { $table->id(); $table->string('name'); $table->string('slug')->unique(); $table->timestamps(); });
        Schema::create('labels', function (Blueprint $table) { $table->id(); $table->foreignId('category_id')->constrained()->cascadeOnDelete(); $table->string('name'); $table->string('slug')->unique(); $table->timestamps(); });
        Schema::create('wahana_photos', function (Blueprint $table) { $table->id(); $table->string('title')->nullable(); $table->text('description')->nullable(); $table->string('photo_path')->nullable(); $table->string('alt_text')->nullable(); $table->boolean('is_featured')->default(false); $table->timestamps(); });
        Schema::create('label_wahana_photo', function (Blueprint $table) { $table->foreignId('label_id')->constrained()->cascadeOnDelete(); $table->foreignId('wahana_photo_id')->constrained()->cascadeOnDelete(); $table->primary(['label_id', 'wahana_photo_id']); });
        Schema::create('events', function (Blueprint $table) { $table->id(); $table->string('title'); $table->text('description')->nullable(); $table->date('event_date'); $table->timestamps(); });
        Schema::create('event_photos', function (Blueprint $table) { $table->id(); $table->foreignId('event_id')->constrained()->cascadeOnDelete(); $table->string('photo_path'); $table->string('alt_text')->nullable(); $table->timestamps(); });
        Schema::create('news', function (Blueprint $table) { $table->id(); $table->string('title'); $table->text('excerpt')->nullable(); $table->string('thumbnail')->nullable(); $table->timestamp('published_at')->nullable(); $table->timestamps(); });
        Schema::create('promotions', function (Blueprint $table) { $table->id(); $table->string('title'); $table->text('description')->nullable(); $table->string('image')->nullable(); $table->date('start_date')->nullable(); $table->date('end_date')->nullable(); $table->timestamps(); });
        Schema::create('partners', function (Blueprint $table) { $table->id(); $table->string('name'); $table->string('logo')->nullable(); $table->timestamps(); });
    }
    public function down(): void
    {
        Schema::dropIfExists('partners'); Schema::dropIfExists('promotions'); Schema::dropIfExists('news'); Schema::dropIfExists('event_photos'); Schema::dropIfExists('events'); Schema::dropIfExists('label_wahana_photo'); Schema::dropIfExists('wahana_photos'); Schema::dropIfExists('labels'); Schema::dropIfExists('categories');
    }
};
