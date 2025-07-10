<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class CategoryFactory extends Factory
{
    public function definition(): array
    {
        // Menggunakan daftar kategori yang sudah ditentukan
        $name = fake()->unique()->randomElement(['Elektronik', 'Fashion', 'Buku', 'Perabotan', 'Pakaian', 'Olahraga', 'Kecantikan']);

        return [
            'name' => $name,
            'slug' => Str::slug($name),
        ];
    }
}
