<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\ReceiptFieldMapping;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Seed Default Administrator User
        User::updateOrCreate(
            ['email' => 'admin@mockengine.com'],
            [
                'name' => 'Administrator',
                'password' => Hash::make('admin123'),
            ]
        );

        // 2. Seed Receipt Field Mappings
        $mappings = [
            [
                'field_key' => 'device_time',
                'x_coordinate' => 75,
                'y_coordinate' => 24,
                'font_size' => 16,
                'font_color' => '#000000',
                'font_weight' => 'bold',
                'text_align' => 'left',
            ],
            [
                'field_key' => 'net_amount',
                'x_coordinate' => 295,
                'y_coordinate' => 166,
                'font_size' => 38,
                'font_color' => '#000000',
                'font_weight' => 'bold',
                'text_align' => 'center',
            ],
            [
                'field_key' => 'network',
                'x_coordinate' => 574,
                'y_coordinate' => 416,
                'font_size' => 16,
                'font_color' => '#1E2329',
                'font_weight' => 'medium',
                'text_align' => 'right',
            ],
            [
                'field_key' => 'address',
                'x_coordinate' => 574,
                'y_coordinate' => 464,
                'font_size' => 16,
                'font_color' => '#1E2329',
                'font_weight' => 'medium',
                'text_align' => 'right',
            ],
            [
                'field_key' => 'txid',
                'x_coordinate' => 574,
                'y_coordinate' => 561,
                'font_size' => 16,
                'font_color' => '#1E2329',
                'font_weight' => 'medium',
                'text_align' => 'right',
            ],
            [
                'field_key' => 'amount',
                'x_coordinate' => 574,
                'y_coordinate' => 664,
                'font_size' => 16,
                'font_color' => '#1E2329',
                'font_weight' => 'medium',
                'text_align' => 'right',
            ],
            [
                'field_key' => 'network_fee',
                'x_coordinate' => 574,
                'y_coordinate' => 720,
                'font_size' => 16,
                'font_color' => '#1E2329',
                'font_weight' => 'medium',
                'text_align' => 'right',
            ],
            [
                'field_key' => 'withdrawal_wallet',
                'x_coordinate' => 574,
                'y_coordinate' => 768,
                'font_size' => 16,
                'font_color' => '#1E2329',
                'font_weight' => 'medium',
                'text_align' => 'right',
            ],
            [
                'field_key' => 'date',
                'x_coordinate' => 574,
                'y_coordinate' => 823,
                'font_size' => 16,
                'font_color' => '#1E2329',
                'font_weight' => 'medium',
                'text_align' => 'right',
            ]
        ];

        foreach ($mappings as $mapping) {
            ReceiptFieldMapping::updateOrCreate(
                ['field_key' => $mapping['field_key']],
                $mapping
            );
        }
    }
}
