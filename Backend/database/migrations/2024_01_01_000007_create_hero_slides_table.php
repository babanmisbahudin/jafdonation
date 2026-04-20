<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('hero_slides', function (Blueprint $table) {
            $table->id();
            $table->string('tag')->nullable();
            $table->string('tag_color')->default('#E55A00');
            $table->string('title_1');
            $table->string('title_2');
            $table->text('description')->nullable();
            $table->text('quote')->nullable();
            $table->string('author')->nullable();
            $table->string('bg_color')->default('#0066cc');
            $table->string('image')->nullable();
            $table->string('cta_text')->default('Dukung Sekarang');
            $table->string('cta_url')->default('/pages/donasi.html');
            $table->integer('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('hero_slides');
    }
};
