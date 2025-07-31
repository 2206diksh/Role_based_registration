<?php



namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'dikshethasriss@gmail.com'],
            [
                'name' => 'Admin',
                'password' => Hash::make('admin123'), // 👈 You can change this password
                'role' => 'admin',
                'is_approved' => true,
            ]
        );
    }
}