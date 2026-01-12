<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Client;

class ClientSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $items = [
            [
                'id' => 1,
                'name' => 'SOLUTEL',
                'pricing_list_id' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'id' => 2,
                'name' => 'ETECSA',
                'pricing_list_id' => 2,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'id' => 3,
                'name' => 'UNE',
                'created_at' => now(),
                'updated_at' => now()
            ],
        ];

        foreach ($items as $item) {
            Client::updateOrCreate(['id' => $item['id']], $item);
        }

    }
}
