<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;
use App\Models\Category;
use App\Models\Product;
use App\Models\User; // <-- Pastikan ini ada
use Illuminate\Support\Facades\Hash; // <-- Pastikan ini ada

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Matikan pengecekan relasi dan kosongkan semua tabel relevan
        Schema::disableForeignKeyConstraints();
        User::truncate();
        Product::truncate();
        Category::truncate();
        Schema::enableForeignKeyConstraints();
        
        // 2. LANGSUNG BUAT ADMIN DI SINI
        User::create([
            'name' => 'Admin Toko',
            'email' => 'admin@toko.com',
            'role' => 'admin',
            'password' => Hash::make('password'), // passwordnya adalah "password"
        ]);

        // 3. Buat kategori
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
