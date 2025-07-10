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
            // Tambahkan kolom shipping_address jika belum ada
            if (!Schema::hasColumn('orders', 'shipping_address')) {
                $table->text('shipping_address')->nullable();
            }
            
            // Set default value untuk shipping_address jika sudah ada
            if (Schema::hasColumn('orders', 'shipping_address')) {
                $table->text('shipping_address')->nullable()->change();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            if (Schema::hasColumn('orders', 'shipping_address')) {
                $table->dropColumn('shipping_address');
            }
        });
    }
};