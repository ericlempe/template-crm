<?php

namespace Database\Seeders;

use App\Models\{Customer, Task};
use Illuminate\Database\Seeder;

class TaskSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Customer::where('id', '<', 10)
            ->each(
                fn (Customer $c) => Task::factory()
                    ->count(random_int(4, 10))
                    ->create(['customer_id' => $c->id])
            );
    }
}
