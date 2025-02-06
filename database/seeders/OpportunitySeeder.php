<?php

namespace Database\Seeders;

use App\Models\Opportunity;
use Illuminate\Database\Seeder;

class OpportunitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $oppotunities = [];

        for ($i = 1; $i <= 100; $i++) {
            $oppotunities[] = Opportunity::factory()->make([
                'customer_id' => rand(1, 70),
            ])->toArray();
        }

        Opportunity::query()->insert($oppotunities);

        Opportunity::factory()->deleted()->count(20)->create();
    }
}
