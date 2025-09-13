<?php

namespace Database\Seeders;

use App\Models\SalesOrder;
use App\Models\SalesOrderItem;
use App\Models\Customer;
use App\Models\SupplyProfile;
use App\Models\User;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class SalesOrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Clear existing sales orders and items (handle foreign key constraints)
        SalesOrderItem::query()->delete();
        SalesOrder::query()->delete();

        $customers = Customer::all();
        $supplyProfiles = SupplyProfile::all();
        $users = User::all();

        if ($customers->isEmpty() || $supplyProfiles->isEmpty() || $users->isEmpty()) {
            $this->command->warn('No customers, supply profiles, or users found. Skipping SalesOrder seeding.');
            return;
        }

        $statuses = ['pending', 'confirmed', 'processing', 'shipped', 'delivered', 'cancelled'];
        $paymentMethods = ['cash', 'gcash', 'paymaya', 'bank_transfer', 'credit_card', 'debit_card', 'cheque', 'cod'];
        $shippingMethods = ['standard', 'express', 'same_day', 'next_day', 'pick_up', 'lalamove', 'grab'];
        $paymentTerms = ['cod', 'cia', 'net_15', 'net_30', 'net_60', 'eom'];

        $contactNames = [
            'John Smith', 'Maria Garcia', 'Michael Wong', 'Emily Martinez', 'David Johnson',
            'Sarah Wilson', 'Robert Brown', 'Lisa Davis', 'James Miller', 'Jennifer Taylor',
            'William Anderson', 'Linda Thomas', 'Christopher Jackson', 'Patricia White',
            'Daniel Harris', 'Nancy Martin', 'Matthew Thompson', 'Karen Garcia', 'Anthony Martinez',
            'Helen Robinson', 'Kevin Clark', 'Sandra Rodriguez', 'Steven Lewis', 'Donna Lee',
            'Edward Walker', 'Carol Hall', 'Brian Allen', 'Ruth Young', 'Ronald King', 'Sharon Wright'
        ];

        // Create 30 sales orders
        for ($i = 0; $i < 30; $i++) {
            $customer = $customers->random();
            $orderDate = Carbon::now()->subDays(rand(1, 180));
            $deliveryDate = $orderDate->copy()->addDays(rand(1, 14));
            $status = $statuses[array_rand($statuses)];
            
            // Adjust delivery date based on status
            if (in_array($status, ['delivered', 'shipped'])) {
                $deliveryDate = $orderDate->copy()->addDays(rand(1, 7));
            } elseif ($status === 'cancelled') {
                $deliveryDate = null;
            }

            $salesOrder = SalesOrder::create([
                'status' => $status,
                'customer_id' => $customer->id,
                'contact_person_name' => $contactNames[array_rand($contactNames)],
                'phone' => '+63 9' . rand(100000000, 999999999),
                'email' => 'customer' . rand(1, 1000) . '@example.com',
                'billing_address' => $customer->address,
                'shipping_address' => $customer->address,
                'customer_reference' => 'REF-' . strtoupper(uniqid()),
                'discounts' => rand(0, 15),
                'payment_method' => $paymentMethods[array_rand($paymentMethods)],
                'shipping_method' => $shippingMethods[array_rand($shippingMethods)],
                'payment_terms' => $paymentTerms[array_rand($paymentTerms)],
                'delivery_date' => $deliveryDate,
                'created_at' => $orderDate,
                'updated_at' => $orderDate,
            ]);

            // Create 1-4 items per sales order
            $numItems = rand(1, 4);

            for ($j = 0; $j < $numItems; $j++) {
                $supply = $supplyProfiles->random();
                $quantity = rand(1, 50);
                $unitPrice = $supply->supply_price1 * (1 + (rand(-10, 20) / 100)); // Vary price by ±10-20%

                SalesOrderItem::create([
                    'sales_order_id' => $salesOrder->id,
                    'product_id' => $supply->id,
                    'description' => $supply->supply_description,
                    'quantity' => $quantity,
                    'unit_price' => $unitPrice,
                    'subtotal' => $quantity * $unitPrice,
                    'created_at' => $orderDate,
                    'updated_at' => $orderDate,
                ]);
            }
        }

        $this->command->info('SalesOrder seeder completed successfully!');
    }
} 