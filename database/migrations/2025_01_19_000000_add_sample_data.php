<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Buat kategori
        Schema::table('categories', function (Blueprint $table) {
            // Cek jika table ada
            if (Schema::hasTable('categories')) {
                // Tambahkan data ke kategori
                DB::table('categories')->insert([
                    ['name' => 'Elektronik', 'slug' => 'elektronik', 'created_at' => now(), 'updated_at' => now()],
                    ['name' => 'Fashion', 'slug' => 'fashion', 'created_at' => now(), 'updated_at' => now()],
                    ['name' => '3D Printing', 'slug' => '3d-printing', 'created_at' => now(), 'updated_at' => now()],
                    ['name' => 'Aksesoris', 'slug' => 'aksesoris', 'created_at' => now(), 'updated_at' => now()],
                ]);
            }
        });

        // Tambahkan produk setelah kategori dibuat
        Schema::table('products', function (Blueprint $table) {
            // Cek jika table ada
            if (Schema::hasTable('products') && Schema::hasTable('categories')) {
                // Cari ID kategori
                $categoryId = DB::table('categories')->where('slug', '3d-printing')->first()->id ?? 1;
                
                // Tambahkan produk ke database
                DB::table('products')->insert([
                    [
                        'name' => 'Gantungan Kunci 3D Superman',
                        'slug' => 'gantungan-kunci-3d-superman',
                        'description' => 'Gantungan kunci dengan desain Superman hasil cetak 3D berkualitas tinggi',
                        'price' => 25000,
                        'category_id' => $categoryId,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]
                ]);
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Bersihkan data
        if (Schema::hasTable('products')) {
            DB::table('products')->truncate();
        }
        
        if (Schema::hasTable('categories')) {
            DB::table('categories')->truncate();
        }
    }
};