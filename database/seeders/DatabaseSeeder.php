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

        User::factory()->create([
            'name' => 'Admin User',
            'email' => 'admin@ecotop.com',
            'password' => bcrypt('password'),
            'role' => 'admin',
        ]);

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => bcrypt('password'),
            'role' => 'user',
        ]);

        $ecosystems = [
            ['day_number' => 1, 'title' => 'Páramos', 'content' => '<p>Contenido sobre los Páramos de Colombia.</p>', 'is_active' => true],
            ['day_number' => 2, 'title' => 'Bosque andino y subandinos', 'content' => '<p>Contenido sobre el Bosque Andino.</p>', 'is_active' => true],
            ['day_number' => 3, 'title' => 'Selvas tropicales', 'content' => '<p>Contenido sobre las Selvas Tropicales.</p>', 'is_active' => true],
            ['day_number' => 4, 'title' => 'Sábanas', 'content' => '<p>Contenido sobre las Sábanas (Orinoquía).</p>', 'is_active' => true],
            ['day_number' => 5, 'title' => 'Ecosistemas acuáticos y marinos', 'content' => '<p>Contenido sobre Ecosistemas Acuáticos y Marinos.</p>', 'is_active' => true],
        ];

        foreach ($ecosystems as $eco) {
            \App\Models\Ecosystem::create($eco);
        }
    }
}
