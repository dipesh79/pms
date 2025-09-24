<?php

namespace Database\Seeders;

use App\Enums\RoleEnum;
use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create 3 admin users
        User::factory(3)->create(
            [
                'role' => RoleEnum::ADMIN->value
            ]
        );

        // Create 3 Managers
        User::factory(3)->create(
            [
                'role' => RoleEnum::MANAGER->value
            ]
        );

        // Create 10 users
        User::factory(10)->create([
            'role' => RoleEnum::USER->value
        ]);
    }
}
