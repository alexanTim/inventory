<?php

namespace Database\Seeders;

use App\Models\SupplyBatch;
use App\Models\SupplyProfile;
use App\Models\SupplyOrder;
use App\Models\User;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class SupplyBatchSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Clear existing batches (handle foreign key constraints)
        SupplyBatch::query()->delete();

        $supplyProfiles = SupplyProfile::all();
        $users = User::all();

        if ($supplyProfiles->isEmpty() || $users->isEmpty()) {
            $this->command->warn('No supply profiles or users found. Skipping SupplyBatch seeding.');
            return;
        }

        $locations = [
            'Warehouse A - Section 1',
            'Warehouse A - Section 2', 
            'Warehouse B - Section 1',
            'Warehouse B - Section 2',
            'Cold Storage - Section 1',
            'Cold Storage - Section 2',
            'Dry Storage - Section 1',
            'Dry Storage - Section 2',
            'Refrigerated Storage',
            'Freezer Storage',
        ];

        $statuses = ['active', 'depleted', 'expired'];
        $notes = [
            'Premium quality batch',
            'Standard production batch',
            'Special order batch',
            'Bulk order batch',
            'Sample batch',
            'Regular stock batch',
            'Promotional batch',
            'Seasonal batch',
            'Limited edition batch',
            'Export quality batch',
        ];

        foreach ($supplyProfiles as $supply) {
            // Create 2-4 batches per supply profile
            $numBatches = rand(2, 4);
            
            for ($i = 0; $i < $numBatches; $i++) {
                $manufacturedDate = Carbon::now()->subDays(rand(30, 365));
                $expirationDate = $manufacturedDate->copy()->addDays(rand(180, 730)); // 6 months to 2 years
                $receivedDate = $manufacturedDate->copy()->addDays(rand(1, 30));
                
                $initialQty = rand(50, 1000);
                $currentQty = $i === 0 ? $initialQty : rand(0, $initialQty); // First batch usually has stock
                $status = $currentQty > 0 ? 'active' : ($expirationDate->isPast() ? 'expired' : 'depleted');
                
                // Generate batch number
                $prefix = strtoupper(substr($supply->supply_sku, 0, 3));
                $date = $manufacturedDate->format('Ymd');
                $sequence = str_pad($i + 1, 3, '0', STR_PAD_LEFT);
                $batchNumber = "{$prefix}{$date}{$sequence}";

                SupplyBatch::create([
                    'supply_profile_id' => $supply->id,
                    'supply_order_id' => null, // Will be linked when we create purchase orders
                    'batch_number' => $batchNumber,
                    'expiration_date' => $expirationDate,
                    'manufactured_date' => $manufacturedDate,
                    'initial_qty' => $initialQty,
                    'current_qty' => $currentQty,
                    'location' => $locations[array_rand($locations)],
                    'notes' => $notes[array_rand($notes)],
                    'status' => $status,
                    'received_date' => $receivedDate,
                    'received_by' => $users->random()->id,
                    'created_at' => $receivedDate,
                    'updated_at' => $receivedDate,
                ]);
            }
        }

        $this->command->info('SupplyBatch seeder completed successfully!');
    }
} 