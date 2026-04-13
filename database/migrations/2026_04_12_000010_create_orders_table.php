<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_no')->unique();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();

            // Müşteri bilgileri (misafir için tekrar saklıyoruz)
            $table->string('customer_name');
            $table->string('customer_email');
            $table->string('customer_phone', 20);

            // Teslimat adresi
            $table->string('shipping_first_name');
            $table->string('shipping_last_name');
            $table->string('shipping_phone', 20);
            $table->string('shipping_city');
            $table->string('shipping_district');
            $table->text('shipping_address');
            $table->string('shipping_zip')->nullable();

            // Fatura adresi
            $table->boolean('billing_same_as_shipping')->default(true);
            $table->string('billing_name')->nullable();
            $table->string('billing_tax_no')->nullable();
            $table->string('billing_tax_office')->nullable();

            // Ödeme
            $table->enum('payment_method', ['iyzico', 'bank_transfer', 'cash_on_delivery'])->default('iyzico');
            $table->enum('payment_status', ['pending', 'paid', 'failed', 'refunded'])->default('pending');

            // Fiyatlar
            $table->decimal('subtotal', 10, 2);
            $table->decimal('shipping_cost', 10, 2)->default(0);
            $table->decimal('discount_amount', 10, 2)->default(0);
            $table->decimal('total', 10, 2);

            // Kupon
            $table->foreignId('coupon_id')->nullable()->constrained()->nullOnDelete();
            $table->string('coupon_code')->nullable();

            // Sipariş durumu
            $table->enum('status', [
                'pending',
                'confirmed',
                'preparing',
                'shipped',
                'delivered',
                'cancelled',
                'refunded'
            ])->default('pending');

            // Kargo
            $table->string('cargo_company')->nullable();
            $table->string('cargo_tracking_no')->nullable();
            $table->string('cargo_tracking_url')->nullable();

            // Notlar
            $table->text('customer_note')->nullable();
            $table->text('admin_note')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
