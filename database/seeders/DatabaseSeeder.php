<?php

namespace Database\Seeders;

use App\Enums\Enum\PermissionEnum;
use App\Enums\RolesEnum;
use App\Models\PurchaseOrder;
use App\Models\RawMatInv;
use App\Models\RawMatProfile;
use App\Models\SupplyOrder;
use App\Models\SupplyProfile;
use App\Models\User;
use App\Models\RequestSlip;
use Database\Seeders\RequestSlipSeeder;
use Database\Seeders\CustomerSeeder;
use Database\Seeders\SupplierSeeder;
use Database\Seeders\SupplyBatchSeeder;
use Database\Seeders\SalesOrderSeeder;
use Database\Seeders\SalesReturnSeeder;
use Database\Seeders\ShipmentSeeder;
use App\Models\ItemType;
use App\Models\Allocation;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;
use Termwind\Components\Raw;
use App\Models\RawMatOrder;
use App\Models\Department;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Clear any existing permissions and roles first
        app()[PermissionRegistrar::class]->forgetCachedPermissions();
        


        // 1. Basic Setup Seeders (must run first)
        $this->call([
            DepartmentSeeder::class,
            RoleAndPermissionSeeder::class,
            ComprehensiveUserSeeder::class,
        ]);

        // 2. Item and Classification Seeders
        $this->call([
            PetGoodsSeeder::class, // This creates ItemTypes and Allocations
            ItemClassSeeder::class,
        ]);

        // 3. Entity Seeders (Customers, Suppliers)
        $this->call([
            CustomerSeeder::class,
            SupplierSeeder::class,
            PetSupplierSeeder::class,
        ]);

        // 4. Test Data Seeders
        $this->call([
            LowStockTestSeeder::class,
        ]);

        // 5. Transaction Seeders (Request Slips, Orders, etc.)
        $this->call([
            RequestSlipSeeder::class,
            SupplyBatchSeeder::class,
            SalesOrderSeeder::class,
            SalesReturnSeeder::class,
            ShipmentSeeder::class,
        ]);

        // 6. Additional Sample Data
        // Create additional pet-specific supply profiles if needed
        SupplyProfile::factory(5)
            ->state(function (array $attributes) {
                // Use only the basic consumable/accessories classes
                $consumableClass = \App\Models\ItemClass::where('name', 'consumable')->first();
                $accessoriesClass = \App\Models\ItemClass::where('name', 'accessories')->first();
                
                return [
                    'item_type_id' => ItemType::inRandomOrder()->first()->id,
                    'allocation_id' => Allocation::inRandomOrder()->first()->id,
                    'item_class_id' => fake()->randomElement([$consumableClass->id, $accessoriesClass->id]),
                    'supply_description' => fake()->randomElement([
                        'Premium Dog Treats - Chicken Flavor',
                        'Cat Litter Box - Large Size',
                        'Fish Tank Heater - 50W',
                        'Hamster Exercise Wheel',
                        'Bird Seed Mix - Premium Blend',
                        'Reptile Heat Lamp - 100W',
                        'Pet Carrier - Medium Size',
                        'Dog Training Clicker',
                        'Cat Grooming Brush',
                        'Aquarium Air Pump'
                    ]),
                    'supply_sku' => 'PET-' . strtoupper(fake()->lexify('???-###')),
                    'supply_uom' => fake()->randomElement(['pc', 'pack', 'box', 'bag', 'sack', 'can', 'bottle', 'tray', 'kg', 'g']),
                ];
            })
            ->create();

        // Create sample purchase orders for pet supplies
        PurchaseOrder::factory(3)
            ->supply()
            ->has(SupplyOrder::factory()->count(3))
            ->create();

        $this->command->info('All seeders completed successfully!');
        $this->command->info('Database has been populated with sample data.');
    }
}
