<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

          \App\Models\User::create([
            'first_name' => 'Super',
            'last_name' => 'Admin',
            'phone' => '0987654325',
            'address' => 'Lattakia',
            'nationality' => 'Syrian',
            'department' => 'UI/UX Designer',
            'designation' => 'designer',
            'country' => 'Syria',
            'email' => 'admin@gmail.com',
            'password' => '12345678',
        ]);

    }
}
