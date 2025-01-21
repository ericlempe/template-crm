<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::factory()
            ->admin()
            ->create([
                'name'  => 'Admin',
                'email' => 'admin@email.com',
            ]);

        User::factory(20)->create();
        User::factory(5)->deleted()->create();
    }
}
