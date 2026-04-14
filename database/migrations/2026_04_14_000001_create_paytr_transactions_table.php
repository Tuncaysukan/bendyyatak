<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('paytr_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->onDelete('cascade');
            $table->string('merchant_oid', 100)->unique();
            $table->string('paytr_token')->nullable();
            $table->decimal('amount', 10, 2);
            $table->string('currency', 3)->default('TRY');
            $table->string('installment_count', 10)->default('1');
            $table->string('card_type', 20)->nullable();
            $table->string('card_brand', 50)->nullable();
            $table->string('card_bank', 50)->nullable();
            $table->string('card_bank_id', 20)->nullable();
            $table->string('card_holder', 100)->nullable();
            $table->string('card_last_four', 4)->nullable();
            $table->string('status', 20)->default('pending');
            $table->text('paytr_response')->nullable();
            $table->text('callback_data')->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->timestamps();

            $table->index(['merchant_oid', 'status']);
            $table->index(['order_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('paytr_transactions');
    }
};
