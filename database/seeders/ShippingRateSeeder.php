<?php

namespace Database\Seeders;

use App\Models\ShippingRate;
use Illuminate\Database\Seeder;

class ShippingRateSeeder extends Seeder
{
    public function run(): void
    {
        $rates = [
            [
                'city' => 'Islamabad',
                'rate' => 150,
                'is_active' => true,
            ],
            [
                'city' => 'Rawalpindi',
                'rate' => 150,
                'is_active' => true,
            ],
            [
                'city' => 'Lahore',
                'rate' => 200,
                'is_active' => true,
            ],
            [
                'city' => 'Karachi',
                'rate' => 250,
                'is_active' => true,
            ],
            [
                'city' => 'Peshawar',
                'rate' => 250,
                'is_active' => true,
            ],
            [
                'city' => 'Quetta',
                'rate' => 300,
                'is_active' => true,
            ],
        ];

        foreach ($rates as $rate) {
            ShippingRate::updateOrCreate(
                [
                    'city' => $rate['city'],
                ],
                $rate
            );
        }
    }
}
