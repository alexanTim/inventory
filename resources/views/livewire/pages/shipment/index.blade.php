<x-slot:header>Shipment</x-slot:header>
<x-slot:subheader>Track and manage all outgoing shipments linked to qualified sales orders.</x-slot:subheader>
<div>
    <div>
        
        <!-- Qualification Summary -->
        <div class="mb-6 p-4 bg-gray-50 border border-gray-200 rounded-lg dark:bg-gray-800 dark:border-gray-700">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-sm font-semibold text-gray-800 dark:text-gray-200 mb-1">
                        Sales Order Shipment Qualification Status
                    </h3>
                    <p class="text-xs text-gray-600 dark:text-gray-400">
                        Currently showing {{ count($salesorder_results ?? []) }} sales orders that qualify for shipment
                    </p>
                </div>
                <button type="button" wire:click="refreshQualifiedSalesOrders" 
                    class="text-xs text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300 flex items-center">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                    </svg>
                    Refresh List
                </button>
            </div>
        </div>

        <!-- QR Scanning Qualification Summary -->
        <div class="mb-6 p-4 bg-green-50 border border-green-200 rounded-lg dark:bg-green-900/20 dark:border-green-800">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-sm font-semibold text-green-800 dark:text-green-300 mb-1">
                        QR Scanning Qualification Status
                    </h3>
                    <p class="text-xs text-green-700 dark:text-green-400">
                        Currently showing {{ count($qrScanningQualifiedShipments ?? []) }} shipments that qualify for QR scanning
                    </p>
                </div>
                <div class="text-xs text-green-600 dark:text-green-400">
                    <span class="font-medium">Active Statuses:</span> Pending, Approved, Ready, Processing, Shipped, In Transit
                </div>
            </div>
        </div>       
@if ($errors->any())
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-2 rounded mb-4">
        <ul class="list-disc pl-5">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
            <div x-data="{ show: @entangle('showQrModal') }" x-show="show" x-cloak
                class="fixed top-0 left-0 right-0 z-50 w-full p-4 overflow-x-hidden overflow-y-auto md:inset-0 h-[calc(100%-1rem)] max-h-full flex items-center justify-center">
                <div class="relative w-full max-w-4xl max-h-full">
                    <div class="relative bg-white rounded-lg shadow dark:bg-zinc-700">
                        <div class="flex items-start justify-between p-4 border-b rounded-t dark:border-zinc-600">
                            <h3 class="text-xl font-semibold text-zinc-900 dark:text-white">
                                Shipment QR Code Details
                            </h3>
                            <button type="button"
                                class="text-zinc-400 bg-transparent hover:bg-zinc-200 hover:text-zinc-900 rounded-lg text-sm w-8 h-8 ml-auto inline-flex justify-center items-center dark:hover:bg-zinc-600 dark:hover:text-white"
                                wire:click="closeQrModal">
                                <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                    fill="none" viewBox="0 0 14 14">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                        stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                                </svg>
                                <span class="sr-only">Close modal</span>
                            </button>
                        </div>
                        <div class="p-6">
                            @if($getShipmentDetails)
                            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                                <!-- QR Code Section -->
                                <div class="flex flex-col items-center justify-center p-6 bg-zinc-50 dark:bg-zinc-800 rounded-lg">
                                    <h4 class="text-lg font-semibold text-zinc-900 dark:text-white mb-4">QR Code</h4>
                                    <div class="bg-white p-4 rounded-lg shadow-sm">
                                        {!! QrCode::size(200)->generate($getShipmentDetails->shipping_plan_num) !!}
                                    </div>
                                    <p class="text-sm text-gray-600 dark:text-gray-400 mt-2 font-mono">
                                        {{ $getShipmentDetails->shipping_plan_num }}
                                    </p>
                                </div>
                                
                                <!-- Purchase Order Details Section -->
                                <div class="space-y-4">
                                    <h4 class="text-lg font-semibold text-zinc-900 dark:text-white">Shippment Details</h4>
                                    <div class="space-y-3">
                                        <div>
                                            <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300">Shipping Plan Number</label>
                                            <p class="text-sm text-zinc-900 dark:text-white font-medium">
                                                {{ $getShipmentDetails->shipping_plan_num }}
                                            </p>
                                        </div>                                        
                                        <div>
                                            <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300">Status</label>
                                            <span class="inline-block px-2 py-1 text-xs font-semibold rounded-full
                                                @if ($getShipmentDetails->shipping_status === 'pending') bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300
                                                @elseif($getShipmentDetails->shipping_status === 'approved') bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300
                                                @elseif($getShipmentDetails->shipping_status === 'cancelled') bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300
                                                @else bg-zinc-100 text-zinc-800 dark:bg-zinc-900 dark:text-zinc-300 @endif">
                                                {{ str_replace('_', ' ', ucfirst($getShipmentDetails->shipping_status)) }}
                                            </span>
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300">Shipping Date</label>
                                            <p class="text-sm text-zinc-900 dark:text-white">
                                                 {{ \Carbon\Carbon::parse($getShipmentDetails->scheduled_ship_date)->format('M d, Y') }}
                                            </p>
                                        </div>                              
                                        <div>
                                            <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300">Total Price</label>
                                            <p class="text-sm text-zinc-900 dark:text-white font-semibold">
                                                ₱{{ number_format($getShipmentDetails->salesOrder->items->sum('subtotal')) }}                                                
                                            </p>
                                        </div>
                                       
                                        @if($getShipmentDetails->approver_id)
                                        <div>
                                            <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300">Approved By</label>
                                            <p class="text-sm text-zinc-900 dark:text-white">                                                
                                                {{ $getShipmentDetails->approver->name }}
                                            </p>
                                        </div>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <!-- Order Items Section -->
                            @if($getShipmentDetails->salesOrder->items->count() > 0)
                            <div class="mt-6">
                                <h4 class="text-lg font-semibold text-zinc-900 dark:text-white mb-4">Order Items</h4>
                                <div class="overflow-x-auto">
                                    <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                                        <thead class="text-xs text-gray-700 uppercase bg-zinc-50 dark:bg-zinc-700 dark:text-gray-400">
                                            <tr>
                                                <th class="px-6 py-3">SKU</th>
                                                <th class="px-6 py-3">Description</th>
                                                <th class="px-6 py-3">Quantity</th>
                                                <th class="px-6 py-3">Unit Price</th>
                                                <th class="px-6 py-3">Total</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($getShipmentDetails->salesOrder->items as $order)
                                            <tr class="odd:bg-white odd:dark:bg-zinc-900 even:bg-zinc-50 even:dark:bg-zinc-800 border-b dark:border-zinc-700">
                                                <td class="px-6 py-4 font-mono">{{ $order->product->supply_sku ?? 'N/A' }}</td>
                                                <td class="px-6 py-4">{{ $order->product->supply_description ?? 'N/A' }}</td>
                                                <td class="px-6 py-4">{{ number_format($order->quantity, 2) }}</td>
                                                <td class="px-6 py-4">₱{{ number_format($order->unit_price, 2) }}</td>
                                                <td class="px-6 py-4 font-semibold">₱{{ number_format($order->quantity * $order->unit_price, 2) }}</td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            @endif
                            @endif
                        </div>
                        <div class="flex items-center justify-end p-6 space-x-2 border-t border-zinc-200 rounded-b dark:border-zinc-600">
                            <x-button type="button" wire:click="closeQrModal" variant="secondary">Close</x-button>
                            @if($getShipmentDetails)
                            <x-button type="button" onclick="window.open('/purchase-order/print/{{ $getShipmentDetails->shipping_plan_num }}', '_blank', 'width=500,height=600')" variant="primary">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
                                </svg>
                                Print QR Code
                            </x-button>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

        <!-- Create Shipment Card -->
       @can(\App\Enums\Enum\PermissionEnum::CREATE_SHIPMENT->value)       
        <x-collapsible-card title="Create New Shipment" open="false" size="full">
            
            <!-- Qualification Criteria Information -->
            <div class="mb-6 p-4 bg-blue-50 border border-blue-200 rounded-lg dark:bg-blue-900/20 dark:border-blue-800">
                <h3 class="text-sm font-semibold text-blue-800 dark:text-blue-300 mb-2">
                    {{ $qualificationCriteria['title'] }}
                </h3>
                <div class="text-xs text-blue-700 dark:text-blue-400 space-y-1">
                    @foreach($qualificationCriteria['criteria'] as $criterion)
                        @if($criterion === '')
                            <br>
                        @else
                            <div>{{ $criterion }}</div>
                        @endif
                    @endforeach
                </div>
            </div>

            <!-- QR Scanning Information -->
            <div class="mb-6 p-4 bg-green-50 border border-green-200 rounded-lg dark:bg-green-900/20 dark:border-green-800">
                <h3 class="text-sm font-semibold text-green-800 dark:text-green-300 mb-2">
                    QR Scanning Qualification Information
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <h4 class="text-xs font-medium text-green-700 dark:text-green-400 mb-2">✅ Qualified for QR Scanning:</h4>
                        <div class="text-xs text-green-600 dark:text-green-400 space-y-1">
                            @foreach($qrScanningCriteria['qualified_statuses'] ?? [] as $status => $description)
                                <div class="flex items-center">
                                    <span class="w-2 h-2 bg-green-400 rounded-full mr-2"></span>
                                    <span class="font-medium">{{ ucfirst($status) }}:</span> {{ $description }}
                                </div>
                            @endforeach
                        </div>
                    </div>
                    <div>
                        <h4 class="text-xs font-medium text-red-700 dark:text-red-400 mb-2">❌ Not Qualified for QR Scanning:</h4>
                        <div class="text-xs text-red-600 dark:text-red-400 space-y-1">
                            @foreach($qrScanningCriteria['disqualified_statuses'] ?? [] as $status => $description)
                                <div class="flex items-center">
                                    <span class="w-2 h-2 bg-red-400 rounded-full mr-2"></span>
                                    <span class="font-medium">{{ ucfirst($status) }}:</span> {{ $description }}
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
          
            <form wire:submit.prevent="createShipment" class="space-y-6">
                        <!-- Order Information Section -->
                        <div class="bg-zinc-50 dark:bg-zinc-700 rounded-lg p-4">
                            <h3 class="text-lg font-semibold text-zinc-900 dark:text-white mb-4 flex items-center">
                                <svg class="w-5 h-5 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                                Shipment Information
                            </h3>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">                   
                                <div>
                                    <div class="space-y-2">
                                        <div class="flex items-center justify-between">
                                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Sales Order Reference</label>
                                            <button type="button" wire:click="refreshQualifiedSalesOrders" 
                                                class="text-xs text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300">
                                                <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                                </svg>
                                                Refresh
                                            </button>
                                        </div>
                                        <select wire:model.live="sales_order_id" 
                                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:ring-blue-400">
                                            <option value="">-- Select Sales Order --</option>
                                            @foreach($salesorder_results ?? [] as $id => $label)
                                                <option value="{{ $id }}">{{ $label }}</option>
                                            @endforeach
                                        </select>
                                        @if(empty($salesorder_results))
                                            <p class="text-xs text-amber-600 dark:text-amber-400 mt-1">
                                                No sales orders qualify for shipment. Only approved, confirmed, or released orders can be shipped.
                                            </p>
                                        @endif
                                    </div>
                                </div> 
                                <div>
                                  
                                    <x-input 
                                        type="date" 
                                        wire:model.defer="scheduled_ship_date" 
                                        name="scheduled_ship_date" 
                                        label="Shipping Date" 
                                        placeholder="Select Shipping Date"
                                        class="w-full"
                                    />
                                </div>

                                <div>
                                  
                                    <x-input 
                                        type="text" 
                                        wire:model.defer="carrier_name" 
                                        name="carrier_name" 
                                        label="Carrier Name" 
                                        placeholder="Carrier Name"
                                        class="w-full"
                                    />
                                </div>    

                                 <div>
                                  
                                    <x-input 
                                        type="text" 
                                        wire:model.defer="vehicle_plate_number" 
                                        name="vehicle_plate_number" 
                                        label="Vehicle Plate Number" 
                                        placeholder="Vehicle Plate Number"
                                        class="w-full"
                                    />
                                </div>                             

                                <div>
                                  <x-input 
                                        type="textarea" 
                                        wire:model.defer="special_handling_notes" 
                                        name="special_handling_notes" 
                                        label="Special Handling Notes" 
                                        placeholder="Special Handling Notes"
                                        class="w-full"
                                    />
                                </div>                                
                            </div>
                        </div>

                        <!-- Customer Information Section -->
                        <div class="bg-zinc-50 dark:bg-zinc-700 rounded-lg p-4">
                            <h3 class="text-lg font-semibold text-zinc-900 dark:text-white mb-4 flex items-center">
                                <svg class="w-5 h-5 mr-2 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                                Customer Information
                            </h3>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <x-input 
                                    type="text" 
                                    wire:model.defer="customer_name" 
                                    name="customer_name" 
                                    label="Customer Name" 
                                    placeholder="Enter Customer Name"
                                />

                                <x-input 
                                    type="text" 
                                    wire:model.defer="customer_address" 
                                    name="customer_address" 
                                    label="Customer Address" 
                                    placeholder="Enter Customer Address"
                                />
                                
                                
                                <x-input 
                                    type="tel" 
                                    wire:model.defer="phone" 
                                    name="phone" 
                                    label="Phone Number" 
                                    placeholder="Enter phone number"
                                />
                                
                                <x-input 
                                    type="email" 
                                    wire:model.defer="email" 
                                    name="email" 
                                    label="Email Address" 
                                    placeholder="Enter email address"
                                /> 
                            </div>
                          </div>

                          <div class="bg-zinc-50 dark:bg-zinc-700 rounded-lg p-4">
                            <h3 class="text-lg font-semibold text-zinc-900 dark:text-white mb-4 flex items-center">
                                <svg class="w-5 h-5 mr-2 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                                Method & Priority
                            </h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4"> 
                              <div>
                                <x-dropdown 
                                      wire:model.live="delivery_method" 
                                      name="delivery_method" 
                                      label="Delivery Method" 
                                      :options="$deliveryMethods" 
                                      placeholder="Select Delivery Method"
                                      class="w-full"
                                  />                                  
                              </div>

                                <div>
                                     <x-dropdown 
                                        wire:model.live="shipping_priority" 
                                        name="delivery_method" 
                                        label="Shipping Priority" 
                                        :options="$shippingPriorityDropdown" 
                                        placeholder="Select Shipping Priority"
                                        class="w-full"
                                    /> 
                                   
                                </div>
                            </div>
                          </div>
                                               

                        <!-- Form Actions -->
                        <div class="flex justify-end pt-4 border-t border-zinc-200 dark:border-zinc-600">
                           
                                <x-button 
                                    type="submit" 
                                    variant="primary"
                                >
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    {{ ($editValue) ? 'Update Shipment' : 'Create Shippment' }}
                                </x-button>
                          
                        </div>
                    </form>
        </x-collapsible-card>
        @endcan
        @if (session()->has('message'))
            <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)"
                class="transition duration-500 ease-in-out" x-transition>
                <x-flash-message />
            </div>
        @endif

        @if (session()->has('error'))          
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-2 rounded mb-4">
                {{ session('error') }}
            </div>
        @endif
            
   
        <!-- Shipment List Card -->
        <x-collapsible-card title="Shipment List" open="true" size="full">
            <section>
                <div>
                    <!-- Start coding here -->
                    <div class="bg-white dark:bg-zinc-800 relative shadow-md sm:rounded-lg overflow-hidden">
                        <div class="flex items-center justify-between p-4 pr-10">
                            <div class="flex space-x-6">
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                        <svg aria-hidden="true" class="w-5 h-5 text-gray-500 dark:text-gray-400"
                                            fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                            <path fill-rule="evenodd"
                                                d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z"
                                                clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                    <input type="text" wire:model.live.debounce.300ms="search"
                                        class="block w-64 p-2 ps-10 text-sm text-zinc-900 border border-zinc-300 rounded-lg bg-gray-50 focus:ring-blue-500 focus:border-blue-500 dark:bg-zinc-700 dark:border-zinc-600 dark:placeholder-zinc-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                        placeholder="Search Shipment..." required="">
                                </div>
                                <div class="flex items-center space-x-2">
                                    <label class="text-sm font-medium text-zinc-900 dark:text-white">Status:</label>
                                    <select wire:model.live="statusFilter"
                                        class="bg-gray-50 border border-zinc-300 text-zinc-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 p-2.5 dark:bg-zinc-700 dark:border-zinc-600 dark:placeholder-zinc-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                        <option value="">All Status</option>                                  
                                        <option value="pending">Pending</option>
                                        <option value="approved">Approved</option>
                                        <option value="processing">Processing</option>
                                        <option value="ready">Ready</option>
                                        <option value="shipped">Shipped</option>
                                        <option value="in_transit">In Transit</option>
                                        <option value="delivered">Delivered</option>
                                        <option value="failed">Failed</option>
                                        <option value="returned">Returned</option>
                                        <option value="cancelled">Cancelled</option>
                                        <option value="incomplete">Incomplete</option>
                                        <option value="damaged">Damaged</option> 
                                    </select>                                
                                </div> 
                            </div>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                                <thead
                                    class="text-sm text-gray-700 uppercase bg-zinc-50 dark:bg-zinc-700 dark:text-gray-400">
                                    <tr>
                                        <th scope="col" class="px-6 py-3">
                                            QR Code
                                        </th>
                                        <th scope="col" class="px-6 py-3">
                                            Shipping Plan #
                                        </th>
                                        <th scope="col" class="px-6 py-3">
                                            Customer
                                        </th>
                                        <th scope="col" class="px-6 py-3">
                                            Status
                                        </th>                                  
                                        <th scope="col" class="px-6 py-3">
                                            Email
                                        </th>                                    
                                        <th scope="col" class="px-6 py-3">
                                            Delivery Method
                                        </th>
                                        <th scope="col" class="px-6 py-3">
                                            Shipping Priority
                                        </th>
                                        <th scope="col" class="px-6 py-3">
                                            Action
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($shipments as $data)
                                        <tr wire:key="{{ $data->id }}"
                                            class="odd:bg-white odd:dark:bg-zinc-900 even:bg-zinc-50 even:dark:bg-zinc-800 border-b dark:border-zinc-700 border-zinc-200">
                                            <td class="px-6 py-4">
                                                <button wire:click="showShipmentQrCode('{{ $data->shipping_plan_num }}')" 
                                                    class="p-1 rounded-full hover:bg-blue-100 dark:hover:bg-blue-900 transform hover:scale-110 transition duration-200">
                                                    {!! QrCode::size(50)->generate($data->shipping_plan_num) !!}
                                                </button>
                                            </td>
                                            <td class="px-6 py-4 font-medium text-zinc-900 whitespace-nowrap dark:text-white">
                                                {{ $data->shipping_plan_num }}
                                            </td>  
                                            <td class="px-6 py-4 font-medium text-zinc-900 whitespace-nowrap dark:text-white">
                                                {{ $data->customer->name ?? $data->customer_name }}
                                            </td>                                      
                                            <td class="px-6 py-4">
                                                <span
                                                    class="px-2 py-1 rounded-full text-white text-xs font-semibold
                                                        @if ($data->shipping_status === 'pending') bg-yellow-500
                                                        @elseif ($data->shipping_status === 'approved') bg-blue-500
                                                        @elseif ($data->shipping_status === 'processing') bg-indigo-500
                                                        @elseif ($data->shipping_status === 'ready') bg-green-500
                                                        @elseif ($data->shipping_status === 'shipped') bg-purple-500
                                                        @elseif ($data->shipping_status === 'in_transit') bg-orange-500
                                                        @elseif ($data->shipping_status === 'delivered') bg-green-600
                                                        @elseif ($data->shipping_status === 'failed') bg-red-500
                                                        @elseif ($data->shipping_status === 'returned') bg-red-600
                                                        @elseif ($data->shipping_status === 'cancelled') bg-red-700
                                                        @elseif ($data->shipping_status === 'incomplete') bg-gray-500
                                                        @elseif ($data->shipping_status === 'damaged') bg-red-800
                                                        @else bg-gray-500 @endif">
                                                    {{ str_replace('_', ' ', ucfirst($data->shipping_status)) }}
                                                </span>
                                            </td>                                       
                                            <td class="px-6 py-4">
                                                {{ $data->customer_email ?? 'N/A' }}
                                            </td>
                                            <td class="px-6 py-4">
                                                {{ ucfirst($data->delivery_method ?? 'N/A') }}
                                            </td>   
                                            <td class="px-6 py-4">
                                                {{ ucfirst(str_replace('-', ' ', $data->shipping_priority ?? 'N/A')) }}
                                            </td>                               
                                            <td class="px-6 py-4">
                                                <div class="flex items-center gap-2">
                                                    <a href="{{ route('shipping.view', $data->id) }}"
                                                        class="p-1 rounded-full hover:bg-blue-100 dark:hover:bg-blue-900 transform hover:scale-110 transition duration-200 text-blue-600 dark:text-blue-500">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24"
                                                            stroke="currentColor" stroke-width="2">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                        </svg>
                                                    </a>
                                                    @can(\App\Enums\Enum\PermissionEnum::EDIT_SHIPMENT->value)                                        
                                                        @if($data->shipping_status == 'pending')
                                                            <button 
                                                                wire:click="edit({{ $data->id }})" 
                                                                @click="window.scrollTo({top: 0, behavior: 'smooth'})"
                                                                class="p-1 rounded-full hover:bg-green-100 dark:hover:bg-green-900 transform hover:scale-110 transition duration-200 text-green-600 dark:text-green-500">
                                                                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24"
                                                                    stroke="currentColor" stroke-width="2">
                                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                                </svg>
                                                            </button>
                                                        @endif
                                                    @endcan
                                                </div>                                           
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="8" class="text-center py-4 text-gray-500 dark:text-gray-400">
                                                No shipments found.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        <div class="py-4 px-3">
                            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                                <!-- Per Page Selection -->
                                <div class="flex items-center space-x-4">
                                    <label for="perPage" class="text-sm font-medium text-zinc-900 dark:text-white">Per
                                        Page
                                    </label>
                                    <select 
                                        id="perPage" 
                                        wire:model.live="perPage"
                                        class="bg-gray-50 border border-zinc-300 text-zinc-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block p-2.5 dark:bg-zinc-700 dark:border-zinc-600 dark:text-white">
                                        <option value="5">5</option>
                                        <option value="10">10</option>
                                        <option value="20">20</option>
                                        <option value="50">50</option>
                                        <option value="100">100</option>
                                    </select>
                                </div>
                                <!-- Pagination Links -->                          
                                <div>
                                    {{ $shipments->links() }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </x-collapsible-card>
    </div>
</div>