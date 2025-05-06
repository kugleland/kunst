<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Database\Seeders\ArtSeeder; 

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Janus Helkjær',
            'email' => 'janus.helkjaer@gmail.com',
            'password' => Hash::make('hemmelighed'),
        ]);
        

        $this->call([
            ArtSeeder::class,
        ]);
    }
}
