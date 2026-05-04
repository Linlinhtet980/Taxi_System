<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Core\Setting;

class SettingSeeder extends Seeder
{
    public function run(): void
    {
        $settings = [
            [
                'key' => 'commission_rate',
                'value' => '15',
                'label' => 'Commission Rate (%)',
                'group' => 'finance'
            ],
            [
                'key' => 'base_fare',
                'value' => '2500',
                'label' => 'Base Fare (MMK)',
                'group' => 'finance'
            ],
            [
                'key' => 'price_per_km',
                'value' => '800',
                'label' => 'Price Per KM (MMK)',
                'group' => 'finance'
            ],

            [
                'key' => 'min_withdrawal',
                'value' => '1000',
                'label' => 'Minimum Withdrawal Amount (MMK)',
                'group' => 'finance'
            ],
            [
                'key' => 'system_name',
                'value' => 'TaxiAdmin',
                'label' => 'System Name',
                'group' => 'general'
            ],
            [
                'key' => 'contact_email',
                'value' => 'admin@taxi.com',
                'label' => 'Contact Email',
                'group' => 'general'
            ],
            [
                'key' => 'vehicle_types',
                'value' => 'Sedan,SUV,Hatchback,Luxury,Van,Truck',
                'label' => 'Available Vehicle Types',
                'group' => 'fleet'
            ],

        ];

        foreach ($settings as $setting) {
            Setting::updateOrCreate(['key' => $setting['key']], $setting);
        }
    }
}
