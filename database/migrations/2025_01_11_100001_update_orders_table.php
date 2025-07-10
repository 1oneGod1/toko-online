<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            // Tambahkan kolom bank_account_id jika belum ada
            if (!Schema::hasColumn('orders', 'bank_account_id')) {
                $table->unsignedBigInteger('bank_account_id')->nullable();
                $table->foreign('bank_account_id')->references('id')->on('bank_accounts')->onDelete('set null');
            }
            
            // Tambahkan kolom subtotal jika belum ada (dibutuhkan untuk checkout)
            if (!Schema::hasColumn('orders', 'subtotal')) {
                $table->decimal('subtotal', 10, 2)->default(0);
            }
            
            // Tambahkan kolom discount jika belum ada
            if (!Schema::hasColumn('orders', 'discount')) {
                $table->decimal('discount', 10, 2)->default(0);
            }
            
            // Tambahkan kolom coupon_code jika belum ada
            if (!Schema::hasColumn('orders', 'coupon_code')) {
                $table->string('coupon_code')->nullable();
            }
            
            // Tambahkan kolom order_number jika belum ada
            if (!Schema::hasColumn('orders', 'order_number')) {
                $table->string('order_number')->nullable();
            }
            
            // Tambahkan kolom name jika belum ada (untuk nama penerima)
            if (!Schema::hasColumn('orders', 'name')) {
                $table->string('name')->nullable();
            }
            
            // Tambahkan kolom address jika belum ada
            if (!Schema::hasColumn('orders', 'address')) {
                $table->text('address')->nullable();
            }
            
            // Tambahkan kolom phone jika belum ada
            if (!Schema::hasColumn('orders', 'phone')) {
                $table->string('phone')->nullable();
            }
            
            // Tambahkan kolom payment_method jika belum ada
            if (!Schema::hasColumn('orders', 'payment_method')) {
                $table->enum('payment_method', ['cod', 'bank_transfer'])->default('cod');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            if (Schema::hasColumn('orders', 'bank_account_id')) {
                $table->dropForeign(['bank_account_id']);
                $table->dropColumn('bank_account_id');
            }
            if (Schema::hasColumn('orders', 'subtotal')) {
                $table->dropColumn('subtotal');
            }
            if (Schema::hasColumn('orders', 'discount')) {
                $table->dropColumn('discount');
            }
            if (Schema::hasColumn('orders', 'coupon_code')) {
                $table->dropColumn('coupon_code');
            }
            if (Schema::hasColumn('orders', 'order_number')) {
                $table->dropColumn('order_number');
            }
            if (Schema::hasColumn('orders', 'name')) {
                $table->dropColumn('name');
            }
            if (Schema::hasColumn('orders', 'address')) {
                $table->dropColumn('address');
            }
            if (Schema::hasColumn('orders', 'phone')) {
                $table->dropColumn('phone');
            }
            if (Schema::hasColumn('orders', 'payment_method')) {
                $table->dropColumn('payment_method');
            }
        });
    }
};