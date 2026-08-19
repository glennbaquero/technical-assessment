<?php

namespace Database\Seeders;

use App\Models\Todo;
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
        $testUser = User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        Todo::factory()->count(5)->for($testUser)->create();
        Todo::factory()->completed()->count(3)->for($testUser)->create();

        User::factory()
            ->count(4)
            ->has(Todo::factory()->count(4), 'todos')
            ->has(Todo::factory()->completed()->count(2), 'todos')
            ->create();
    }
}
