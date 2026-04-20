<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('donations', function (Blueprint $table) {
            $table->id();
            $table->string('order_id')->unique();
            $table->string('donor_name');
            $table->string('donor_email');
            $table->string('donor_phone')->nullable();
            $table->foreignId('program_id')->nullable()->constrained()->nullOnDelete();
            $table->decimal('amount', 15, 2);
            $table->string('payment_method')->nullable();
            $table->enum('payment_status', ['pending', 'paid', 'failed', 'expired', 'refund'])->default('pending');
            $table->string('midtrans_transaction_id')->nullable();
            $table->text('midtrans_token')->nullable();
            $table->text('midtrans_redirect_url')->nullable();
            $table->json('midtrans_response')->nullable();
            $table->text('note')->nullable();
            $table->boolean('is_anonymous')->default(false);
            $table->timestamp('paid_at')->nullable();
            $table->timestamps();

            $table->index(['payment_status', 'created_at']);
            $table->index('donor_email');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('donations');
    }
};
