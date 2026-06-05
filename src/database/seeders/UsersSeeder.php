<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::factory()->create([
            'email' => 'admin@example.com',
            'role' => 'admin',
            'password' => Hash::make('password')
        ]);
        User::factory()->create([
            'email' => 'user@example.com',
            'role' => 'user',
            'password' => Hash::make('password')
        ]);
    }
}
