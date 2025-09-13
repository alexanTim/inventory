<?php

namespace Database\Seeders;

use App\Models\SalesReturn;
use App\Models\SalesReturnItem;
use App\Models\SalesOrder;
use App\Models\SalesOrderItem;
use App\Models\User;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class SalesReturnSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Clear existing sales returns and items (handle foreign key constraints)
        SalesReturnItem::query()->delete();
        SalesReturn::query()->delete();

        $deliveredSalesOrders = SalesOrder::where('status', 'delivered')->get();
        $users = User::all();

        if ($deliveredSalesOrders->isEmpty() || $users->isEmpty()) {
            $this->command->warn('No delivered sales orders or users found. Skipping SalesReturn seeding.');
            return;
        }

        $returnTypes = [true, false]; // true = full return, false = partial return
        $reasons = [
            'Damaged during shipping',
            'Wrong item received',
            'Quality issues',
            'Customer changed mind',
            'Size/color not as expected',
            'Expired product',
            'Defective item',
            'Not suitable for pet',
            'Allergic reaction',
            'Better alternative found',
            'Financial constraints',
            'Moving to different location',
            'Pet passed away',
            'Duplicate order',
            'Incorrect quantity shipped'
        ];

        // Create returns for 20% of delivered orders
        $ordersToReturn = $deliveredSalesOrders->random(min(10, $deliveredSalesOrders->count()));

        foreach ($ordersToReturn as $salesOrder) {
            $returnDate = $salesOrder->delivery_date->addDays(rand(1, 30));
            $isFullReturn = $returnTypes[array_rand($returnTypes)];
            $reason = $reasons[array_rand($reasons)];
            
            // Calculate total refund
            $totalRefund = 0;

            $salesReturn = SalesReturn::create([
                'customer_id' => $salesOrder->customer_id,
                'sales_order_id' => $salesOrder->id,
                'return_date' => $returnDate,
                'is_full_return' => $isFullReturn,
                'reason' => $reason,
                'return_reference' => 'RET-' . strtoupper(uniqid()),
                'total_refund' => 0, // Will be calculated
                'processed_by' => $users->random()->id,
                'created_at' => $returnDate,
                'updated_at' => $returnDate,
            ]);

            // Create return items
            $salesOrderItems = $salesOrder->items;
            
            foreach ($salesOrderItems as $orderItem) {
                $returnQuantity = $isFullReturn ? $orderItem->quantity : rand(1, $orderItem->quantity);
                $refundAmount = $returnQuantity * $orderItem->unit_price;
                $totalRefund += $refundAmount;

                SalesReturnItem::create([
                    'sales_return_id' => $salesReturn->id,
                    'product_id' => $orderItem->product_id,
                    'quantity' => $returnQuantity,
                    'unit_price' => $orderItem->unit_price,
                    'total_price' => $refundAmount,
                    'condition' => rand(0, 1) ? 'good' : 'damaged',
                    'notes' => $reason,
                    'created_at' => $returnDate,
                    'updated_at' => $returnDate,
                ]);
            }

            // Update total refund
            $salesReturn->update(['total_refund' => $totalRefund]);
        }

        $this->command->info('SalesReturn seeder completed successfully!');
    }
} 