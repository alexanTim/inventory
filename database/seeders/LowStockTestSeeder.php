<?php

namespace Database\Seeders;

use App\Models\ItemType;
use App\Models\ItemClass;
use App\Models\Allocation;
use App\Models\SupplyProfile;
use Illuminate\Database\Seeder;

class LowStockTestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get existing item types, classes, and allocations
        $itemTypes = ItemType::all();
        $itemClasses = ItemClass::all();
        $allocations = Allocation::all();

        if ($itemTypes->isEmpty() || $itemClasses->isEmpty() || $allocations->isEmpty()) {
            $this->command->warn('Required ItemTypes, ItemClasses, or Allocations not found. Please run other seeders first.');
            return;
        }

        // Create test products with various stock levels for testing the low stock filter
        $testProducts = [
            // OUT OF STOCK (0 units) - Red badges
            [
                'supply_sku' => 'TEST-OUT-001',
                'supply_description' => 'Test Product - Out of Stock (0 units)',
                'supply_qty' => 0,
                'supply_uom' => 'pc',
                'supply_min_qty' => 5,
                'supply_price1' => 15.99,
                'supply_price2' => 14.99,
                'supply_price3' => 13.99,
                'unit_cost' => 10.00,
                'low_stock_threshold_percentage' => 20,
                'item_class_id' => $itemClasses->where('name', 'consumable')->first()->id,
                'item_type_id' => $itemTypes->first()->id,
                'allocation_id' => $allocations->first()->id,
            ],
            [
                'supply_sku' => 'TEST-OUT-002',
                'supply_description' => 'Test Product - Completely Depleted (0 units)',
                'supply_qty' => 0,
                'supply_uom' => 'kg',
                'supply_min_qty' => 2,
                'supply_price1' => 25.99,
                'supply_price2' => 24.99,
                'supply_price3' => 23.99,
                'unit_cost' => 18.00,
                'low_stock_threshold_percentage' => 15,
                'item_class_id' => $itemClasses->where('name', 'consumable')->first()->id,
                'item_type_id' => $itemTypes->first()->id,
                'allocation_id' => $allocations->first()->id,
            ],

            // CRITICAL STOCK (1-5 units) - Orange badges
            [
                'supply_sku' => 'TEST-CRIT-001',
                'supply_description' => 'Test Product - Critical Stock (1 unit)',
                'supply_qty' => 1,
                'supply_uom' => 'pc',
                'supply_min_qty' => 3,
                'supply_price1' => 12.99,
                'supply_price2' => 11.99,
                'supply_price3' => 10.99,
                'unit_cost' => 8.00,
                'low_stock_threshold_percentage' => 25,
                'item_class_id' => $itemClasses->where('name', 'consumable')->first()->id,
                'item_type_id' => $itemTypes->first()->id,
                'allocation_id' => $allocations->first()->id,
            ],
            [
                'supply_sku' => 'TEST-CRIT-002',
                'supply_description' => 'Test Product - Critical Stock (3 units)',
                'supply_qty' => 3,
                'supply_uom' => 'pc',
                'supply_min_qty' => 5,
                'supply_price1' => 18.99,
                'supply_price2' => 17.99,
                'supply_price3' => 16.99,
                'unit_cost' => 12.00,
                'low_stock_threshold_percentage' => 20,
                'item_class_id' => $itemClasses->where('name', 'consumable')->first()->id,
                'item_type_id' => $itemTypes->first()->id,
                'allocation_id' => $allocations->first()->id,
            ],
            [
                'supply_sku' => 'TEST-CRIT-003',
                'supply_description' => 'Test Product - Critical Stock (5 units)',
                'supply_qty' => 5,
                'supply_uom' => 'pc',
                'supply_min_qty' => 8,
                'supply_price1' => 22.99,
                'supply_price2' => 21.99,
                'supply_price3' => 20.99,
                'unit_cost' => 15.00,
                'low_stock_threshold_percentage' => 30,
                'item_class_id' => $itemClasses->where('name', 'consumable')->first()->id,
                'item_type_id' => $itemTypes->first()->id,
                'allocation_id' => $allocations->first()->id,
            ],

            // LOW STOCK (6-10 units) - Yellow badges
            [
                'supply_sku' => 'TEST-LOW-001',
                'supply_description' => 'Test Product - Low Stock (6 units)',
                'supply_qty' => 6,
                'supply_uom' => 'pc',
                'supply_min_qty' => 10,
                'supply_price1' => 16.99,
                'supply_price2' => 15.99,
                'supply_price3' => 14.99,
                'unit_cost' => 11.00,
                'low_stock_threshold_percentage' => 20,
                'item_class_id' => $itemClasses->where('name', 'consumable')->first()->id,
                'item_type_id' => $itemTypes->first()->id,
                'allocation_id' => $allocations->first()->id,
            ],
            [
                'supply_sku' => 'TEST-LOW-002',
                'supply_description' => 'Test Product - Low Stock (8 units)',
                'supply_qty' => 8,
                'supply_uom' => 'pc',
                'supply_min_qty' => 12,
                'supply_price1' => 19.99,
                'supply_price2' => 18.99,
                'supply_price3' => 17.99,
                'unit_cost' => 13.00,
                'low_stock_threshold_percentage' => 25,
                'item_class_id' => $itemClasses->where('name', 'consumable')->first()->id,
                'item_type_id' => $itemTypes->first()->id,
                'allocation_id' => $allocations->first()->id,
            ],
            [
                'supply_sku' => 'TEST-LOW-003',
                'supply_description' => 'Test Product - Low Stock (10 units)',
                'supply_qty' => 10,
                'supply_uom' => 'pc',
                'supply_min_qty' => 15,
                'supply_price1' => 24.99,
                'supply_price2' => 23.99,
                'supply_price3' => 22.99,
                'unit_cost' => 16.00,
                'low_stock_threshold_percentage' => 30,
                'item_class_id' => $itemClasses->where('name', 'consumable')->first()->id,
                'item_type_id' => $itemTypes->first()->id,
                'allocation_id' => $allocations->first()->id,
            ],

            // HEALTHY STOCK (11+ units) - Green badges
            [
                'supply_sku' => 'TEST-HEALTHY-001',
                'supply_description' => 'Test Product - Healthy Stock (15 units)',
                'supply_qty' => 15,
                'supply_uom' => 'pc',
                'supply_min_qty' => 5,
                'supply_price1' => 28.99,
                'supply_price2' => 27.99,
                'supply_price3' => 26.99,
                'unit_cost' => 20.00,
                'low_stock_threshold_percentage' => 20,
                'item_class_id' => $itemClasses->where('name', 'consumable')->first()->id,
                'item_type_id' => $itemTypes->first()->id,
                'allocation_id' => $allocations->first()->id,
            ],
            [
                'supply_sku' => 'TEST-HEALTHY-002',
                'supply_description' => 'Test Product - Healthy Stock (25 units)',
                'supply_qty' => 25,
                'supply_uom' => 'pc',
                'supply_min_qty' => 8,
                'supply_price1' => 32.99,
                'supply_price2' => 31.99,
                'supply_price3' => 30.99,
                'unit_cost' => 22.00,
                'low_stock_threshold_percentage' => 25,
                'item_class_id' => $itemClasses->where('name', 'consumable')->first()->id,
                'item_type_id' => $itemTypes->first()->id,
                'allocation_id' => $allocations->first()->id,
            ],
            [
                'supply_sku' => 'TEST-HEALTHY-003',
                'supply_description' => 'Test Product - Healthy Stock (50 units)',
                'supply_qty' => 50,
                'supply_uom' => 'pc',
                'supply_min_qty' => 10,
                'supply_price1' => 35.99,
                'supply_price2' => 34.99,
                'supply_price3' => 33.99,
                'unit_cost' => 25.00,
                'low_stock_threshold_percentage' => 30,
                'item_class_id' => $itemClasses->where('name', 'consumable')->first()->id,
                'item_type_id' => $itemTypes->first()->id,
                'allocation_id' => $allocations->first()->id,
            ],

            // accessories with various stock levels
            [
                'supply_sku' => 'TEST-ACC-001',
                'supply_description' => 'Test Accessory - Low Stock (7 units)',
                'supply_qty' => 7,
                'supply_uom' => 'pc',
                'supply_min_qty' => 10,
                'supply_price1' => 45.99,
                'supply_price2' => 44.99,
                'supply_price3' => 43.99,
                'unit_cost' => 30.00,
                'low_stock_threshold_percentage' => 20,
                'item_class_id' => $itemClasses->where('name', 'accessories')->first()->id,
                'item_type_id' => $itemTypes->first()->id,
                'allocation_id' => $allocations->first()->id,
            ],
            [
                'supply_sku' => 'TEST-ACC-002',
                'supply_description' => 'Test Accessory - Critical Stock (2 units)',
                'supply_qty' => 2,
                'supply_uom' => 'pc',
                'supply_min_qty' => 5,
                'supply_price1' => 55.99,
                'supply_price2' => 54.99,
                'supply_price3' => 53.99,
                'unit_cost' => 35.00,
                'low_stock_threshold_percentage' => 25,
                'item_class_id' => $itemClasses->where('name', 'accessories')->first()->id,
                'item_type_id' => $itemTypes->first()->id,
                'allocation_id' => $allocations->first()->id,
            ],
        ];

        $createdCount = 0;
        foreach ($testProducts as $product) {
            $created = SupplyProfile::updateOrCreate(
                ['supply_sku' => $product['supply_sku']],
                $product
            );
            
            if ($created->wasRecentlyCreated) {
                $createdCount++;
            }
        }

        $this->command->info("Low Stock Test Seeder completed!");
        $this->command->info("Created/Updated {$createdCount} test products with various stock levels:");
        $this->command->info("- Out of Stock (0 units): 2 products");
        $this->command->info("- Critical Stock (1-5 units): 3 products");
        $this->command->info("- Low Stock (6-10 units): 3 products");
        $this->command->info("- Healthy Stock (11+ units): 3 products");
        $this->command->info("- accessories with low/critical stock: 2 products");
        $this->command->info("");
        $this->command->info("You can now test the low stock filter with these products!");
    }
}
