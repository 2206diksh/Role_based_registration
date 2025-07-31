<?php

namespace Database\Seeders;
use Illuminate\Database\Seeder;
use App\models\User;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            AdminSeeder::class,
        ]);
       
    // Generate 30 random users
    User::factory()->count(30)->create();
    }
}
