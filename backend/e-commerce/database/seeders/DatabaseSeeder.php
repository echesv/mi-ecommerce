<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

       // Crear un usuario de prueba
        User::create([
            'name' => 'Pedro Marmol',
            'email' => 'pmarmol@caricaturas.com',
            'password' => bcrypt('password123'),
            'phone' => '+503 7123 4567',
            'address' => 'Calle 14 Av. Sur No. 123',
            'city' => 'San Salvador',
            'postal_code' => '01101',
        ]);

        User::create([
            'name' => 'Betty La Fea',
            'email' => 'b.fea@ecomoda.com',
            'password' => bcrypt('password123'),
            'phone' => '+503 7234 5678',
            'address' => 'Calle 7 Av. Este No. 456',
            'city' => 'Santa Ana',
            'postal_code' => '02101',
        ]);

        // Crear 8 usuarios aleatorios
        User::factory(8)->create();

        // Ejecutar otros seeders
        $this->call([
            ProductSeeder::class,
        ]);
    }
}
