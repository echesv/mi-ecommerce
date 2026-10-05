<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Crear 10 productos con la factory
        Product::factory(10)->create();

        // O crear productos específicos manualmente
        Product::create([
            'name' => 'Laptop Dell XPS 13',
            'description' => 'Laptop ultraligera de alta performance',
            'price' => 1299.99,
            'quantity' => 15,
            'sku' => 'DELL-XPS-13',
            'status' => 'active',
        ]);

        Product::create([
            'name' => 'iPhone 15 Pro',
            'description' => 'Smartphone de última generación',
            'price' => 999.99,
            'quantity' => 25,
            'sku' => 'IPHONE-15-PRO',
            'status' => 'active',
        ]);

        Product::create([
            'name' => 'AirPods Pro',
            'description' => 'Auriculares inalámbricos con cancelación de ruido',
            'price' => 249.99,
            'quantity' => 50,
            'sku' => 'AIRPODS-PRO',
            'status' => 'active',
        ]);

        Product::create([
            'name' => 'Samsung 65" 4K TV',
            'description' => 'Televisor 4K con HDR y SmartTV',
            'price' => 799.99,
            'quantity' => 8,
            'sku' => 'SAMSUNG-TV-65',
            'status' => 'active',
        ]);

        Product::create([
            'name' => 'PlayStation 5',
            'description' => 'Consola de videojuegos de nueva generación',
            'price' => 499.99,
            'quantity' => 12,
            'sku' => 'PS5-CONSOLE',
            'status' => 'active',
        ]);
    }
}
