<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class ProductFactory extends Factory
{
    public function definition(): array
    {
        // Kombinasi kata-kata Bahasa Indonesia untuk nama produk
        $adjectives = ['Cepat', 'Modern', 'Klasik', 'Minimalis', 'Elegan', 'Terbaru', 'Premium', 'Portabel', 'Serbaguna'];
        $nouns = ['Meja Belajar', 'Lampu Tidur', 'Kemeja Flanel', 'Sepatu Lari', 'Ponsel Canggih', 'Kamera Mirrorless', 'Novel Fantasi', 'Kursi Santai'];
        
        $name = fake('id_ID')->randomElement($adjectives) . ' ' . fake('id_ID')->randomElement($nouns) . ' ' . fake('id_ID')->unique()->numberBetween(100, 999);

        return [
            'name' => $name,
            'slug' => Str::slug($name),
            'description' => 'Deskripsi lengkap dan terperinci untuk produk ' . $name . '. Dibuat dari bahan berkualitas tinggi yang menjamin ketahanan dan kenyamanan saat digunakan. ' . fake('id_ID')->paragraph(2),
            'price' => fake()->numberBetween(5, 200) * 10000, // Harga kelipatan 10.000
            'image' => 'https://placehold.co/600x400/0D47A1/FFFFFF?text=' . Str::slug(fake()->randomElement($nouns)),
        ];
    }
}
