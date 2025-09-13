<?php

namespace App\Livewire\Pages\Shipment;

use App\Models\SalesOrder;
use App\Models\Shipment;
use App\Models\StockMovement;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
class Index extends Component
{
    use WithPagination; 
    
    public $shippingPriorityDropdown = [
        'same-day'=> 'Same Day', // For deliveries within the same day
        'next-day'=> 'Next Day', // Promises delivery on the following day
        'normal'  => 'Normal',   // Default, regular delivery  
        'scheduled'=>'Scheduled' , //Customer chooses a specific delivery date/time
        'backorder' => 'Backorder', // Delivery delayed until stock is available
        'rush'    => 'Rush', // Prioritized processing and delivery
        'express' => 'Express', // Express – Fastest available delivery
    ];

    public $showQrModal = false;
    public $getShipmentDetails = null;
    public $perPage = 10;
    public $salesOrders;
    public $shipping_plan_num = '';
    public $sales_order_id;   
    public $customer_name;
    public $customer_address;
    public $scheduled_ship_date;
    public $delivery_method;
    public $carrier_name;
    public $vehicle_plate_number;
    public $shipping_priority = '';
    public $special_handling_notes;
    public $filterStatus = '';
    public $search = '';
    public $salesOrderResults = [];
    public $phone = '';
    public $email = '';
    public $editValue = null;
    public $statusFilter = '';
        
    public $deliveryMethods = [
        'courier', 
        'pickup', 
        'truck', 
        'motorbike', 
        'in-house', 
        'cargo'
    ];


    public function updatingSearch() { $this->resetPage(); }
    public function updatingFilterStatus() { $this->resetPage(); }

    public function mount()
    {
        $this->deliveryMethods = Shipment::deliveryMethodDropDown();
        $this->loadQualifiedSalesOrders();
    }

    /**
     * Load sales orders that qualify for shipment
     * Orders must be approved, confirmed, or released to be shipped
     */
    public function loadQualifiedSalesOrders()
    {
        $this->salesOrders = SalesOrder::whereIn('status', ['approved', 'confirmed', 'released'])
            ->whereDoesntHave('shipments', function($query) {
                // Exclude orders that already have shipments
                $query->where('shipping_status', '!=', 'cancelled');
            })
            ->with(['customer', 'items'])
            ->orderBy('created_at', 'desc')
            ->get();
    }

    /**
     * Get the list of qualified sales order statuses for shipment
     */
    public function getQualifiedStatuses()
    {
        return [
            'approved' => 'Approved - Order approved and ready for processing',
            'confirmed' => 'Confirmed - Order confirmed and ready for fulfillment',
            'released' => 'Released - Order released after stock-out processing'
        ];
    }

    /**
     * Get qualification criteria explanation for the UI
     */
    public function getQualificationCriteria()
    {
        return [
            'title' => 'Sales Order Shipment Qualification Criteria',
            'criteria' => [
                'Only sales orders with these statuses can be shipped:',
                '• Approved - Order has been approved for processing',
                '• Confirmed - Order has been confirmed and is ready for fulfillment',
                '• Released - Order has been released after stock-out processing',
                '',
                'Orders cannot be shipped if:',
                '• They are still pending or processing',
                '• They have been rejected, cancelled, or put on hold',
                '• They already have an active shipment',
                '• They have been fully shipped and delivered'
            ]
        ];
    }

    /**
     * Get QR scanning qualification criteria
     */
    public function getQrScanningCriteria()
    {
        return \App\Models\Shipment::getQrScanningCriteria();
    }

    /**
     * Get shipments qualified for QR scanning
     */
    public function getQrScanningQualifiedShipments()
    {
        return \App\Models\Shipment::whereIn('shipping_status', ['pending', 'approved', 'ready', 'processing', 'shipped', 'in_transit'])
            ->with(['customer', 'salesOrder'])
            ->get();
    }

    /**
     * Refresh the qualified sales orders list
     */
    public function refreshQualifiedSalesOrders()
    {
        $this->loadQualifiedSalesOrders();
        $this->dispatch('sales-orders-refreshed');
    }
    
    public function edit($id){
        
        $results = Shipment::with('customer')->find($id);

        if($results){
            if($results->shipping_status == 'pending'){
                $this->sales_order_id = $results->sales_order_id;
                $this->scheduled_ship_date = $results->scheduled_ship_date;
                $this->carrier_name = $results->carrier_name;
                $this->vehicle_plate_number = $results->vehicle_plate_number;
                $this->special_handling_notes = $results->special_handling_notes;
                $this->customer_name = $results->customer_name;
                $this->customer_address = $results->customer_address;
                $this->phone = $results->customer_phone;
                $this->email = $results->customer_email;
                $this->delivery_method = $results->delivery_method;
                $this->shipping_priority = $results->shipping_priority;                
                $this->editValue = $id;   
            }else{
                session()->flash('error', 'You can only edit shipments that are in pending status.');
            }
        }        
    }

    public function showShipmentQrCode($shipping_plan_num)
    {
        $this->showQrModal = true;
        $this->getShipmentDetails = Shipment::where('shipping_plan_num',$shipping_plan_num)
            ->with(['salesOrder.items.product'])
            ->first();        
    }

    public function updatedStatusFilter($value){
        $this->statusFilter = $value;
    }
 
    public function updatedSalesOrderId($value)
    {
        $order = SalesOrder::with('customer')->find($value);

        if ($order) {
            $this->customer_name = $order->customer->name ?? '';
            $this->customer_address = $order->customer->address ?? '';
            $this->phone = $order->customer->contact_num ?? '';           
        } else {
            $this->customer_name = '';
            $this->customer_address = '';
            $this->phone = '';
        }
    }

    public function createShipment()
    {
        $this->validate([
            'sales_order_id' => 'required|exists:sales_orders,id',
            'scheduled_ship_date' => [
                'required',
                'date',
                function ($attribute, $value, $fail) {
                    if (strtotime($value) < strtotime('today')) {
                        $fail('The scheduled ship date cannot be in the past.');
                    }
                },
            ],
            'delivery_method' => 'required|string|max:255',
            'carrier_name' => 'required|nullable|string|max:255',
            'vehicle_plate_number' => 'nullable|string|max:255',
            'shipping_priority' => ['required', Rule::in(['normal', 'rush', 'express','scheduled','backorder','next-day','same-day'])],            
            'customer_name' => 'required|string|max:255',
            'phone' => 'required|string|max:255',
            'email' => 'string|max:255',
            'customer_address' => 'required|string|max:255',
        ]);

        DB::beginTransaction();

        try {
            if($this->editValue == null){
                Shipment::create([
                    'sales_order_id' => $this->sales_order_id,                   
                    'customer_id' => SalesOrder::find($this->sales_order_id)->customer->id,
                    'customer_name' => $this->customer_name,
                    'customer_address' => $this->customer_address,
                    'scheduled_ship_date' => $this->scheduled_ship_date,
                    'delivery_method' => $this->delivery_method,
                    'carrier_name' => $this->carrier_name,
                    'vehicle_plate_number' => $this->vehicle_plate_number,
                    'shipping_priority' => $this->shipping_priority,
                    'special_handling_notes' => $this->special_handling_notes,  
                    'customer_email' => $this->email,            
                    'customer_phone' => $this->phone,                                      
                ]);
            }else{

                $Shipment = Shipment::find($this->editValue);
                $ShipmentData = [
                    'sales_order_id' => $this->sales_order_id,                   
                    'customer_id' => SalesOrder::find($this->sales_order_id)->customer->id,
                    'customer_name' => $this->customer_name,
                    'customer_address' => $this->customer_address,
                    'scheduled_ship_date' => $this->scheduled_ship_date,
                    'delivery_method' => $this->delivery_method,
                    'carrier_name' => $this->carrier_name,
                    'vehicle_plate_number' => $this->vehicle_plate_number,
                    'shipping_priority' => $this->shipping_priority,
                    'special_handling_notes' => $this->special_handling_notes,
                    'customer_email' => $this->email,            
                    'customer_phone' => $this->phone                    
                ];
             
                $Shipment->update($ShipmentData);

            }

            DB::commit();

            session()->flash('success', 'Shipment created successfully.');
            $this->resetForm();

        } catch (\Exception $e) {
            DB::rollBack();
            $this->addError('shipment_error', $e->getMessage());
        }
    }

    public function closeQrModal()
    {
        $this->showQrModal = false;
        $this->getShipmentDetails = null;
    }

    protected function resetForm()
    {
        $this->reset([
            'sales_order_id',
            'customer_name',
            'customer_address',
            'scheduled_ship_date',
            'delivery_method',
            'carrier_name',
            'vehicle_plate_number',
            'shipping_priority',
            'special_handling_notes',
            'editValue'
        ]);
    }

    public function markAsShipped($id)
    {
        $shipment = Shipment::findOrFail($id);
        $shipment->update([
            'shipping_status' => 'shipped',
            'shipped_at' => now(),
        ]);
    }

    public function markAsDelivered($id)
    {
        $shipment = Shipment::findOrFail($id);
        $shipment->update([
            'shipping_status' => 'delivered',
            'delivered_at' => now(),
        ]);
    }

    public function render()
    {
        // Get qualified sales orders for shipment dropdown
        $qualifiedSalesOrders = $this->salesOrders->map(function($order) {
            $customerName = $order->customer ? $order->customer->name : 'Unknown Customer';
            $itemCount = $order->items ? $order->items->count() : 0;
            $totalAmount = $order->items ? $order->items->sum('subtotal') : 0;
            
            return [
                'id' => $order->id,
                'label' => "{$order->sales_order_number} - {$customerName} ({$order->status}) - {$itemCount} items - ₱" . number_format($totalAmount, 2)
            ];
        })->pluck('label', 'id');

        $query = Shipment::with('customer');

        return view('livewire.pages.shipment.index', [
            'shipments' => $query->search( $this->search )->filterStatus($this->statusFilter)
                ->latest()
                ->paginate($this->perPage),
            'salesorder_results' => $qualifiedSalesOrders,
            'qualificationCriteria' => $this->getQualificationCriteria(),
            'qualifiedStatuses' => $this->getQualifiedStatuses(),
            'qrScanningCriteria' => $this->getQrScanningCriteria(),
            'qrScanningQualifiedShipments' => $this->getQrScanningQualifiedShipments(),
        ]);
    }
}