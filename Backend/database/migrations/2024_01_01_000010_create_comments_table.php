<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('comments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('article_id')->constrained()->cascadeOnDelete();
            $table->string('guest_name');
            $table->string('guest_email')->nullable();
            $table->text('content');
            $table->string('ip_address', 45)->nullable();
            $table->boolean('is_approved')->default(false);
            $table->boolean('is_spam')->default(false);
            $table->unsignedTinyInteger('spam_score')->default(0); // 0-100
            $table->text('spam_reasons')->nullable();              // JSON
            $table->timestamps();

            $table->index(['article_id', 'is_approved']);
            $table->index('is_spam');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('comments');
    }
};
