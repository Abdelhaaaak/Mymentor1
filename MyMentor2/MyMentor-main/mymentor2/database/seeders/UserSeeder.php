<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Create 10 mentors
        User::factory()->count(20)->create(['role' => 'mentor']);

        // Create 10 mentees
        User::factory()->count(10)->create(['role' => 'mentee']);
    }
}
