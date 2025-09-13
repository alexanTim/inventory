<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\ItemClass;

class ItemClassSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // First, ensure the basic classes exist (these are created by the migration)
        $consumableClass = ItemClass::firstOrCreate(
            ['name' => 'consumable'],
            ['description' => 'Consumable items that are used up or depleted']
        );
        
        $accessoriesClass = ItemClass::firstOrCreate(
            ['name' => 'accessories'],
            ['description' => 'Accessory items that are not consumed']
        );

        // Additional specialized item classes
        $itemClasses = [
            [
                'name' => 'Premium Pet Food',
                'description' => 'High-quality premium pet food products with premium ingredients',
            ],
            [
                'name' => 'Standard Pet Food',
                'description' => 'Standard quality pet food products for regular use',
            ],
            [
                'name' => 'Pet Toys',
                'description' => 'Various types of pet toys and entertainment items',
            ],
            [
                'name' => 'Pet Care Products',
                'description' => 'Pet grooming, hygiene, and care products',
            ],
            [
                'name' => 'Pet Health Supplies',
                'description' => 'Pet health and medical supplies and supplements',
            ],
            [
                'name' => 'Pet Bedding',
                'description' => 'Pet beds, blankets, and comfort items',
            ],
            [
                'name' => 'Pet Training Equipment',
                'description' => 'Pet training tools and equipment',
            ],
            [
                'name' => 'Pet Safety Items',
                'description' => 'Pet safety and security products',
            ],
            [
                'name' => 'Office Supplies',
                'description' => 'General office supplies and stationery',
            ],
            [
                'name' => 'Packaging Materials',
                'description' => 'Various packaging materials and supplies',
            ],
        ];

        foreach ($itemClasses as $itemClass) {
            ItemClass::updateOrCreate(
                ['name' => $itemClass['name']],
                $itemClass
            );
        }
    }
}
