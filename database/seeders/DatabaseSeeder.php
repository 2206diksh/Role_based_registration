<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create 10 approved users
        User::factory()->count(10)->create([
            'is_approved' => true,
        ]);

        // Create 20 unapproved users
        User::factory()->count(20)->unapproved()->create();
    }
}
