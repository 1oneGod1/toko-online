<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;
use App\Models\Category;
use App\Models\Product;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Matikan pengecekan relasi, kosongkan tabel, lalu nyalakan kembali
        Schema::disableForeignKeyConstraints();
        Product::truncate();
        Category::truncate();
        Schema::enableForeignKeyConstraints();

        // 2. Panggil Seeder untuk membuat akun admin
        $this->call([
            AdminUserSeeder::class
        ]);
        
        // 3. Buat kategori secara spesifik
        Category::create(['name' => 'Elektronik', 'slug' => 'elektronik']);
        Category::create(['name' => 'Fashion', 'slug' => 'fashion']);
        Category::create(['name' => 'Buku', 'slug' => 'buku']);
        Category::create(['name' => 'Perabotan', 'slug' => 'perabotan']);
        Category::create(['name' => 'Pakaian', 'slug' => 'pakaian']);
        Category::create(['name' => 'Olahraga', 'slug' => 'olahraga']);
        Category::create(['name' => 'Kecantikan', 'slug' => 'kecantikan']);

        // 4. Untuk setiap kategori yang ada di database, buat 5 produk
        Category::all()->each(function ($category) {
            Product::factory(5)->create([
                'category_id' => $category->id,
            ]);
        });
    }
}