<?php

namespace Database\Seeders;

use App\Models\Shipment;
use App\Models\ShipmentStatusLog;
use App\Models\Customer;
use App\Models\SalesOrder;
use App\Models\User;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class ShipmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Clear existing shipments and status logs (handle foreign key constraints)
        ShipmentStatusLog::query()->delete();
        Shipment::query()->delete();

        $customers = Customer::all();
        $qualifiedSalesOrders = SalesOrder::whereIn('status', ['approved', 'confirmed', 'released'])->get();
        $users = User::all();

        if ($customers->isEmpty() || $qualifiedSalesOrders->isEmpty() || $users->isEmpty()) {
            $this->command->warn('No customers, qualified sales orders, or users found. Skipping Shipment seeding.');
            return;
        }

        $deliveryMethods = ['courier', 'pickup', 'truck', 'motorbike', 'in-house', 'cargo'];
        $carrierNames = [
            'Lalamove Express', 'Grab Express', 'J&T Express', 'JRS Express', 'LBC Express',
            'Ninja Van', 'GoGo Xpress', '2GO Express', 'Air21', 'DHL Express',
            'FedEx', 'UPS', 'In-House Delivery', 'Local Courier', 'Regional Transport'
        ];
        $shippingPriorities = ['same-day', 'next-day', 'normal', 'scheduled', 'backorder', 'rush', 'express'];
        $shippingStatuses = ['pending', 'approved', 'ready', 'processing', 'shipped', 'in_transit', 'delivered'];
        $vehiclePlateNumbers = [
            'ABC-123', 'XYZ-789', 'DEF-456', 'GHI-789', 'JKL-012',
            'MNO-345', 'PQR-678', 'STU-901', 'VWX-234', 'YZA-567',
            'BCD-890', 'EFG-123', 'HIJ-456', 'KLM-789', 'NOP-012'
        ];

        $specialHandlingNotes = [
            'Handle with care - fragile items',
            'Keep refrigerated',
            'Do not stack heavy items on top',
            'Deliver during business hours only',
            'Require signature upon delivery',
            'Call customer before delivery',
            'Leave at front desk if no answer',
            'Handle with care - pet food',
            'Keep dry and away from heat',
            'Fragile - glass containers',
            'Heavy items - use trolley',
            'Time-sensitive delivery',
            'Special packaging required',
            'Temperature controlled',
            'Security clearance required'
        ];

        // Create shipments with various statuses for comprehensive testing
        $statusDistribution = [
            'pending' => 3,      // Awaiting approval
            'approved' => 4,     // Approved and ready
            'ready' => 3,        // Ready for pickup
            'processing' => 3,   // Being processed
            'shipped' => 4,      // In transit
            'in_transit' => 3,   // Active delivery
            'delivered' => 3     // Successfully delivered
        ];

        $shipmentCount = 0;
        $maxShipments = 25; // Limit total shipments

        foreach ($statusDistribution as $status => $count) {
            if ($shipmentCount >= $maxShipments) break;
            
            for ($i = 0; $i < $count && $shipmentCount < $maxShipments; $i++) {
                $salesOrder = $qualifiedSalesOrders->random();
                $customer = $salesOrder->customer;
                $scheduledShipDate = $this->getScheduledDateForStatus($status);
                
                $shipment = Shipment::create([
                    'customer_id' => $customer->id,
                    'sales_order_id' => $salesOrder->id,
                    'customer_name' => $customer->name,
                    'customer_address' => $customer->address ?? 'Sample Address',
                    'delivery_method' => $deliveryMethods[array_rand($deliveryMethods)],
                    'carrier_name' => $carrierNames[array_rand($carrierNames)],
                    'vehicle_plate_number' => $vehiclePlateNumbers[array_rand($vehiclePlateNumbers)],
                    'shipping_priority' => $shippingPriorities[array_rand($shippingPriorities)],
                    'special_handling_notes' => $specialHandlingNotes[array_rand($specialHandlingNotes)],
                    'shipping_status' => $status,
                    'scheduled_ship_date' => $scheduledShipDate,
                    'customer_email' => $salesOrder->email ?? 'customer' . $customer->id . '@example.com',
                    'customer_phone' => $salesOrder->phone ?? $customer->contact_num ?? '09' . rand(100000000, 999999999),
                    'approver_id' => $status !== 'pending' ? $users->random()->id : null,
                ]);

                // Create status logs for the shipment
                $this->createStatusLogs($shipment, $users, $status);
                
                $shipmentCount++;
                
                // Small delay to ensure unique timestamps
                usleep(100000); // 0.1 second delay
            }
        }

        // Create some additional test shipments with edge cases
        $this->createEdgeCaseShipments($qualifiedSalesOrders, $users, $deliveryMethods, $carrierNames, $shippingPriorities, $specialHandlingNotes, $vehiclePlateNumbers);

        $this->command->info("Shipment seeder completed successfully! Created {$shipmentCount} shipments.");
        $this->command->info('Status distribution: ' . json_encode($statusDistribution));
    }

    /**
     * Get appropriate scheduled date based on shipment status
     */
    private function getScheduledDateForStatus($status)
    {
        $now = Carbon::now();
        
        switch ($status) {
            case 'pending':
                return $now->addDays(rand(1, 3));
            case 'approved':
                return $now->addDays(rand(1, 2));
            case 'ready':
                return $now->addDays(rand(0, 1));
            case 'processing':
                return $now->addDays(rand(0, 1));
            case 'shipped':
                return $now->subDays(rand(1, 3));
            case 'in_transit':
                return $now->subDays(rand(2, 5));
            case 'delivered':
                return $now->subDays(rand(5, 10));
            default:
                return $now->addDays(rand(1, 3));
        }
    }

    /**
     * Create edge case shipments for testing
     */
    private function createEdgeCaseShipments($qualifiedSalesOrders, $users, $deliveryMethods, $carrierNames, $shippingPriorities, $specialHandlingNotes, $vehiclePlateNumbers)
    {
        // Create a same-day delivery shipment
        $salesOrder1 = $qualifiedSalesOrders->random();
        $customer1 = $salesOrder1->customer;
        $sameDayShipment = Shipment::create([
            'customer_id' => $customer1->id,
            'sales_order_id' => $salesOrder1->id,
            'customer_name' => $customer1->name,
            'customer_address' => $customer1->address ?? 'Urgent Delivery Address',
            'delivery_method' => 'courier',
            'carrier_name' => 'Express Delivery Service',
            'vehicle_plate_number' => 'URG-001',
            'shipping_priority' => 'same-day',
            'special_handling_notes' => 'URGENT - Same day delivery required',
            'shipping_status' => 'processing',
            'scheduled_ship_date' => Carbon::today(),
            'customer_email' => $salesOrder1->email ?? 'urgent@example.com',
            'customer_phone' => $salesOrder1->phone ?? $customer1->contact_num ?? '09123456789',
        ]);

        // Create a rush shipment
        $salesOrder2 = $qualifiedSalesOrders->where('id', '!=', $salesOrder1->id)->random();
        $customer2 = $salesOrder2->customer;
        $rushShipment = Shipment::create([
            'customer_id' => $customer2->id,
            'sales_order_id' => $salesOrder2->id,
            'customer_name' => $customer2->name,
            'customer_address' => $customer2->address ?? 'Rush Delivery Address',
            'delivery_method' => 'truck',
            'carrier_name' => 'Rush Transport Co.',
            'vehicle_plate_number' => 'RUSH-002',
            'shipping_priority' => 'rush',
            'special_handling_notes' => 'RUSH - High priority shipment',
            'shipping_status' => 'approved',
            'scheduled_ship_date' => Carbon::tomorrow(),
            'customer_email' => $salesOrder2->email ?? 'rush@example.com',
            'customer_phone' => $salesOrder2->phone ?? $customer2->contact_num ?? '09876543210',
        ]);

        // Create status logs for edge case shipments
        $this->createStatusLogs($sameDayShipment, $users, 'processing');
        $this->createStatusLogs($rushShipment, $users, 'approved');
    }

    /**
     * Create comprehensive status logs for shipment tracking
     */
    private function createStatusLogs($shipment, $users, $currentStatus)
    {
        $statusFlow = [
            'pending' => ['pending'],
            'approved' => ['pending', 'approved'],
            'ready' => ['pending', 'approved', 'ready'],
            'processing' => ['pending', 'approved', 'ready', 'processing'],
            'shipped' => ['pending', 'approved', 'ready', 'processing', 'shipped'],
            'in_transit' => ['pending', 'approved', 'ready', 'processing', 'shipped', 'in_transit'],
            'delivered' => ['pending', 'approved', 'ready', 'processing', 'shipped', 'in_transit', 'delivered']
        ];

        $statuses = $statusFlow[$currentStatus] ?? ['pending'];
        $startTime = $shipment->created_at;
        
        foreach ($statuses as $index => $status) {
            $logDate = $startTime->addHours($index * rand(2, 6));
            
            ShipmentStatusLog::create([
                'shipment_id' => $shipment->id,
                'status' => $status,
                'changed_at' => $logDate,
                'changed_by' => $users->random()->id,
                'created_at' => $logDate,
                'updated_at' => $logDate,
            ]);
        }
    }

    /**
     * Get descriptive note for each status
     */
    private function getStatusNote($status)
    {
        $notes = [
            'pending' => 'Shipment created and awaiting approval',
            'approved' => 'Shipment approved and scheduled for pickup',
            'ready' => 'Shipment ready for pickup by carrier',
            'processing' => 'Shipment being processed for shipping',
            'shipped' => 'Shipment picked up and in transit to destination',
            'in_transit' => 'Shipment actively being delivered',
            'delivered' => 'Shipment successfully delivered to customer',
        ];

        return $notes[$status] ?? 'Status updated';
    }
} 