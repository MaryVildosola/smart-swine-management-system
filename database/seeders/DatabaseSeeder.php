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
        // 1. Create Default Admin
        User::firstOrCreate(
            ['email' => 'admin@porcitrack.com'],
            [
                'name' => 'System Administrator',
                'password' => \Illuminate\Support\Facades\Hash::make('admin123'),
                'role' => 'admin',
                'status' => true,
            ]
        );

        // 2. Create Default Worker
        User::firstOrCreate(
            ['email' => 'worker@porcitrack.com'],
            [
                'name' => 'Juan Dela Cruz',
                'password' => \Illuminate\Support\Facades\Hash::make('password123'),
                'role' => 'farm_worker',
                'status' => true,
            ]
        );

        // 3. Run Mock Data Seeders
        $this->call([
            FarmMockDataSeeder::class,
            FeedIngredientSeeder::class,
            PenSeeder::class,
        ]);

        \Illuminate\Support\Facades\Log::info('Database seeding completed successfully.');
    }
}
