<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('programs', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->longText('content')->nullable();
            $table->string('thumbnail')->nullable();
            $table->foreignId('category_id')->nullable()->constrained()->nullOnDelete();
            $table->decimal('target_amount', 15, 2)->default(0);
            $table->decimal('collected_amount', 15, 2)->default(0);
            $table->string('region')->nullable();
            $table->enum('status', ['active', 'completed', 'paused'])->default('active');
            $table->boolean('is_featured')->default(false);
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('programs');
    }
};
