<?php

namespace Database\Seeders;

use App\Models\SmartHome;
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

        // User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
        
        $object = ['tv', 'lampu', 'ac', 'pintu', 'kulkas', 'oven'];
        foreach ($object as $item) {
            SmartHome::create([
                'name' => $item,
                'status' => 0
            ]);
        }
    }
}
