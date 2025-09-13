<x-slot:header>Sales Order</x-slot:header>
<x-slot:subheader>Click the button to create a sales order. After that, you can view and edit its details below.</x-slot:subheader>
<div>
    <div>

        <!-- Search Bar -->

            {{-- <x-modal wire:model="showCreateModal" class="max-h-[80vh]">
                <h2 class="text-xl font-bold mb-4">QrCode Details</h2>
                <form>
                    @foreach($getSalesOrderDetails ?? [] as $values)

                    @endforeach
                </form>
            </x-modal> --}}

            <div x-data="{ show: @entangle('showQrModal') }" x-show="show" x-cloak
                class="fixed top-0 left-0 right-0 z-50 w-full p-4 overflow-x-hidden overflow-y-auto md:inset-0 h-[calc(100%-1rem)] max-h-full flex items-center justify-center">
                <div class="relative w-full max-w-4xl max-h-full">
                    <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
                        <div class="flex items-start justify-between p-4 border-b rounded-t dark:border-gray-600">
                            <h3 class="text-xl font-semibold text-gray-900 dark:text-white">
                                Sales Order QR Code Details
                            </h3>
                            <button type="button"
                                class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ml-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white"
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
                            @if($getSalesOrderDetails)
                            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                                <!-- QR Code Section -->
                                <div class="flex flex-col items-center justify-center p-6 bg-gray-50 dark:bg-gray-800 rounded-lg">
                                    <h4 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">QR Code</h4>
                                    <div class="bg-white p-4 rounded-lg shadow-sm">
                                        {!! QrCode::size(200)->generate($getSalesOrderDetails->sales_order_number) !!}
                                    </div>
                                    <p class="text-sm text-gray-600 dark:text-gray-400 mt-2 font-mono">{{ $getSalesOrderDetails->sales_order_number }}</p>
                                </div>
                                
                                <!-- Purchase Order Details Section -->
                                <div class="space-y-4">
                                    <h4 class="text-lg font-semibold text-gray-900 dark:text-white">Sales Order Details</h4>
                                    <div class="space-y-3">
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Sales Order Number</label>
                                            <p class="text-sm text-gray-900 dark:text-white font-medium">{{ $getSalesOrderDetails->sales_order_number }}</p>
                                        </div>                                        
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Status</label>
                                            <span class="inline-block px-2 py-1 text-xs font-semibold rounded-full
                                                @if ($getSalesOrderDetails->status === 'pending') bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300
                                                @elseif($getSalesOrderDetails->status === 'approved') bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300
                                                @elseif($getSalesOrderDetails->status === 'rejected') bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300
                                                @else bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-300 @endif">
                                                {{ str_replace('_', ' ', ucfirst($getSalesOrderDetails->status)) }}
                                            </span>
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Sales Order Date</label>
                                            <p class="text-sm text-gray-900 dark:text-white">{{ $getSalesOrderDetails->created_at->format('M d, Y') }}</p>
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Delivery Date</label>
                                            <p class="text-sm text-gray-900 dark:text-white">{{ $getSalesOrderDetails->delivery_date ? $getSalesOrderDetails->delivery_date->format('M d, Y') : 'N/A' }}</p>
                                        </div>                                        
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Payment Terms</label>
                                            <p class="text-sm text-gray-900 dark:text-white">{{ $getSalesOrderDetails->payment_terms ?? 'N/A' }}</p>
                                        </div>                                       
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Total Price</label>
                                            <p class="text-sm text-gray-900 dark:text-white font-semibold">₱{{ number_format($getSalesOrderDetails->items->sum('subtotal')) }}</p>
                                        </div>
                                       
                                        @if($getSalesOrderDetails->approver)
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Approved By</label>
                                            <p class="text-sm text-gray-900 dark:text-white">
                                                <?php 
                                                    $UserName = '';
                                                    $getuser = \App\Models\User::where('id', $getSalesOrderDetails->approver)->first();
                                                    if($getuser){
                                                        $UserName = $getuser->name;
                                                    }
                                                ?>
                                                {{ $UserName }}</p>
                                        </div>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <!-- Order Items Section -->
                            @if($getSalesOrderDetails->items->count() > 0)
                            <div class="mt-6">
                                <h4 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Order Items</h4>
                                <div class="overflow-x-auto">
                                    <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                                        <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                                            <tr>
                                                <th class="px-6 py-3">SKU</th>
                                                <th class="px-6 py-3">Description</th>
                                                <th class="px-6 py-3">Quantity</th>
                                                <th class="px-6 py-3">Unit Price</th>
                                                <th class="px-6 py-3">Total</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($getSalesOrderDetails->items as $order)
                                            <tr class="odd:bg-white odd:dark:bg-gray-900 even:bg-gray-50 even:dark:bg-gray-800 border-b dark:border-gray-700">
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
                        <div class="flex items-center justify-end p-6 space-x-2 border-t border-gray-200 rounded-b dark:border-gray-600">
                            <x-button type="button" wire:click="closeQrModal" variant="secondary">Close</x-button>
                            @if($getSalesOrderDetails)
                            <x-button type="button" onclick="window.open('/purchase-order/print/{{ $getSalesOrderDetails->sales_order_number }}', '_blank', 'width=500,height=600')" variant="primary">
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

        <!-- Create Sales Order Card -->
        @can(\App\Enums\Enum\PermissionEnum::CREATE_SALES_ORDER->value)           
        <x-collapsible-card title="Create New Sales Order" open="false" size="full">
            @if (session()->has('salesorderExisterror'))          
                <div class="mb-4 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg">
                    <div class="flex">
                        <svg class="w-5 h-5 text-red-400 mr-2 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                        </svg>
                        <span class="text-sm font-medium">{{ session('salesorderExisterror') }}</span>
                    </div>
                </div>
            @endif

            <form wire:submit.prevent="submitOrder" class="space-y-6">
                        <!-- Order Information Section -->
                        <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 flex items-center">
                                <svg class="w-5 h-5 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                                Order Information
                            </h3>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                                <div>
                                    <x-dropdown 
                                        wire:model.defer="status" 
                                        name="status" 
                                        label="Order Status" 
                                        :options="[
                                            'pending' => 'Pending',
                                            'confirmed' => 'Confirmed', 
                                            'processing' => 'Processing',
                                            'shipped' => 'Shipped',
                                            'delivered' => 'Delivered',
                                            'cancelled' => 'Cancelled',
                                            'returned' => 'Returned',
                                            'on hold' => 'On Hold'
                                        ]"
                                        placeholder="Select Status"
                                        class="w-full"
                                    />
                                </div>
                                
                                <div>
                                    <x-dropdown 
                                        wire:model.live="customerSelected" 
                                        name="customerSelected" 
                                        label="Customer" 
                                        :options="$company_results" 
                                        placeholder="Select Customer"
                                        class="w-full"
                                    />
                                </div>
                                
                                <div>
                                    <x-input 
                                        type="text" 
                                        wire:model.defer="customerReference" 
                                        name="customerReference" 
                                        label="Customer Reference #" 
                                        placeholder="Enter reference number"
                                        class="w-full"
                                    />
                                </div>
                            </div>
                            
                            <!-- Customer Information Display -->
                            @if(!empty($customerData))
                            <div class="mt-4 p-3 bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg">
                                <h4 class="text-sm font-medium text-blue-900 dark:text-blue-100 mb-2 flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    Selected Customer Information
                                </h4>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-3 text-sm">
                                    <div>
                                        <span class="text-blue-700 dark:text-blue-300 font-medium">Name:</span>
                                        <span class="text-blue-900 dark:text-blue-100 ml-1">{{ $customerData['name'] ?? 'N/A' }}</span>
                                    </div>
                                    <div>
                                        <span class="text-blue-700 dark:text-blue-300 font-medium">Contact:</span>
                                        <span class="text-blue-900 dark:text-blue-100 ml-1">{{ $customerData['contact_num'] ?? 'N/A' }}</span>
                                    </div>
                                    <div>
                                        <span class="text-blue-700 dark:text-blue-300 font-medium">TIN:</span>
                                        <span class="text-blue-900 dark:text-blue-100 ml-1">{{ $customerData['tin_num'] ?? 'N/A' }}</span>
                                    </div>
                                    <div class="md:col-span-2">
                                        <span class="text-blue-700 dark:text-blue-300 font-medium">Address:</span>
                                        <span class="text-blue-900 dark:text-blue-100 ml-1">{{ $customerData['address'] ?? 'N/A' }}</span>
                                    </div>
                                </div>
                            </div>
                            @endif
                        </div>

                        <!-- Customer Information Section -->
                        <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 flex items-center">
                                <svg class="w-5 h-5 mr-2 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                                Customer Information
                            </h3>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <x-input 
                                    type="text" 
                                    wire:model.defer="contactPersonName" 
                                    name="contactPersonName" 
                                    label="Contact Person Name" 
                                    placeholder="Enter contact person name"
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
                                
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        Delivery Date
                                    </label>
                                    <input 
                                        type="date" 
                                        wire:model.defer="deliveryDate"
                                        name="deliveryDate"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                        min="{{ date('Y-m-d') }}"
                                    >
                                    @error('deliveryDate') 
                                        <span class="text-sm text-red-600">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        Billing Address
                                    </label>
                                    <textarea 
                                        wire:model.defer="billingAddress" 
                                        name="billingAddress" 
                                        rows="3"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                        placeholder="Enter billing address"
                                    ></textarea>
                                    @error('billingAddress') 
                                        <span class="text-sm text-red-600">{{ $message }}</span>
                                    @enderror
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        Shipping Address
                                    </label>
                                    <textarea 
                                        wire:model.defer="shippingAddress" 
                                        name="shippingAddress" 
                                        rows="3"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                        placeholder="Enter shipping address"
                                    ></textarea>
                                    @error('shippingAddress') 
                                        <span class="text-sm text-red-600">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Payment & Shipping Section -->
                        <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 flex items-center">
                                <svg class="w-5 h-5 mr-2 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                                </svg>
                                Payment & Shipping
                            </h3>
                            
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <x-dropdown 
                                    wire:model.defer="paymentMethod" 
                                    name="paymentMethod" 
                                    label="Payment Method" 
                                    :options="$paymentMethodDropdown" 
                                    placeholder="Select Payment Method"
                                />
                                
                                <x-dropdown 
                                    wire:model.defer="shippingMethod" 
                                    name="shippingMethod" 
                                    label="Shipping Method" 
                                    :options="$shippingMethodDropDown" 
                                    placeholder="Select Shipping Method"
                                />
                                
                                <x-dropdown 
                                    wire:model.defer="paymentTerms" 
                                    name="paymentTerms" 
                                    label="Payment Terms" 
                                    :options="$paymentTermsDropdown" 
                                    placeholder="Select Payment Terms"
                                />
                            </div>
                        </div>

                        <!-- Product Items Section -->
                        <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4">
                            <div class="flex items-center justify-between mb-4">
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-white flex items-center">
                                    <svg class="w-5 h-5 mr-2 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                                    </svg>
                                    Product Items
                                </h3>
                                <button 
                                    type="button" 
                                    wire:click="addItem" 
                                    class="inline-flex items-center px-3 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 focus:ring-4 focus:ring-blue-300"
                                >
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                    </svg>
                                    Add Item
                                </button>
                            </div>

                            @php
                                $itemErrors = collect($errors->getMessages())
                                    ->filter(fn($_, $key) => Str::startsWith($key, 'items.'));
                            @endphp

                            @if ($itemErrors->isNotEmpty())
                                <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg mb-4">
                                    <div class="flex">
                                        <svg class="w-5 h-5 text-red-400 mr-2 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                                        </svg>
                                        <div>
                                            <p class="text-sm font-medium">Please fix the following errors:</p>
                                            <ul class="mt-1 text-sm list-disc list-inside">
                                                @foreach ($itemErrors->flatten() as $error)
                                                    <li>{{ $error }}</li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            @endif

                            <div class="overflow-x-auto max-h-96">
                                <table class="w-full border border-gray-200 dark:border-gray-600 rounded-lg overflow-hidden">
                                    <thead class="bg-gray-100 dark:bg-gray-600">
                                        <tr>
                                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">#</th>
                                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Product</th>
                                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Stock</th>
                                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Quantity</th>
                                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Unit Price</th>
                                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Subtotal</th>
                                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white dark:bg-gray-700 divide-y divide-gray-200 dark:divide-gray-600">
                                        @foreach($items as $index => $item)
                                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-600">
                                            <td class="px-4 py-3 text-sm text-gray-900 dark:text-white">{{ $index + 1 }}</td>
                                            <td class="px-4 py-3">
                                                <select 
                                                    wire:model.live="items.{{ $index }}.product_id" 
                                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white text-sm"
                                                >
                                                    <option value="">Select Product</option>
                                                    @foreach($product_list ?? [] as $key => $supply)
                                                        @php
                                                            $product = \App\Models\SupplyProfile::find($key);
                                                        @endphp
                                                        <option value="{{ $key }}">
                                                            {{ $supply }} 
                                                            @if($product)
                                                                (SKU: {{ $product->supply_sku }}, Stock: {{ $product->supply_qty }} {{ $product->supply_uom }})
                                                            @endif
                                                        </option>
                                                    @endforeach
                                                </select>
                                                @error("items.{$index}.product_id") 
                                                    <span class="text-xs text-red-600">{{ $message }}</span>
                                                @enderror
                                            </td>
                                            <td class="px-4 py-3 text-sm">
                                                @if($item['product_id'])
                                                    @php
                                                        $product = \App\Models\SupplyProfile::find($item['product_id']);
                                                    @endphp
                                                    @if($product)
                                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium
                                                            @if($product->supply_qty > 50) bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300
                                                            @elseif($product->supply_qty > 10) bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300
                                                            @else bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300 @endif">
                                                            {{ $product->supply_qty }} {{ $product->supply_uom }}
                                                        </span>
                                                    @else
                                                        <span class="text-gray-500 dark:text-gray-400">-</span>
                                                    @endif
                                                @else
                                                    <span class="text-gray-500 dark:text-gray-400">-</span>
                                                @endif
                                            </td>
                                            <td class="px-4 py-3">
                                                <input 
                                                    type="number" 
                                                    wire:model.live="items.{{ $index }}.quantity" 
                                                    name="items.{{ $index }}.quantity" 
                                                    min="1" 
                                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white text-sm"
                                                    placeholder="Qty"
                                                >
                                                @error("items.{$index}.quantity") 
                                                    <span class="text-xs text-red-600">{{ $message }}</span>
                                                @enderror
                                            </td>
                                            <td class="px-4 py-3">
                                                <select 
                                                    wire:model.live="items.{{ $index }}.unit_price" 
                                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white text-sm"
                                                >
                                                    <option value="">Select Price</option>
                                                    @foreach($items[$index]['price_option'] as $key => $price)
                                                        <option value="{{ $price }}">₱{{ number_format($price, 2) }}</option>
                                                    @endforeach
                                                </select>
                                                @error("items.{$index}.unit_price") 
                                                    <span class="text-xs text-red-600">{{ $message }}</span>
                                                @enderror
                                            </td>
                                            <td class="px-4 py-3 text-sm text-gray-900 dark:text-white">
                                                @if($item['quantity'] && $item['unit_price'])
                                                    ₱{{ number_format($item['quantity'] * $item['unit_price'], 2) }}
                                                @else
                                                    ₱0.00
                                                @endif
                                            </td>
                                            <td class="px-4 py-3 text-center">
                                                @if($index != 0)
                                                    <button 
                                                        type="button" 
                                                        wire:click="removeItem({{ $index }})"
                                                        class="text-red-600 hover:text-red-800 dark:hover:text-red-400"
                                                        title="Remove item"
                                                    >
                                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                        </svg>
                                                    </button>
                                                @endif
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <!-- Order Summary -->
                            @if(count($items) > 0)
                            <div class="mt-4 p-4 bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-600">
                                <h4 class="text-lg font-semibold text-gray-900 dark:text-white mb-3">Order Summary</h4>
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm">
                                    <div>
                                        <span class="text-gray-600 dark:text-gray-400">Total Items:</span>
                                        <span class="font-medium text-gray-900 dark:text-white ml-2">{{ count(array_filter($items, fn($item) => !empty($item['product_id']))) }}</span>
                                    </div>
                                    <div>
                                        <span class="text-gray-600 dark:text-gray-400">Total Quantity:</span>
                                        <span class="font-medium text-gray-900 dark:text-white ml-2">{{ array_sum(array_column($items, 'quantity')) }}</span>
                                    </div>
                                    <div>
                                        <span class="text-gray-600 dark:text-gray-400">Total Amount:</span>
                                        <span class="font-bold text-lg text-blue-600 dark:text-blue-400 ml-2">
                                            ₱{{ number_format(array_sum(array_map(fn($item) => ($item['quantity'] ?? 0) * ($item['unit_price'] ?? 0), $items)), 2) }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                            @endif
                        </div>

                        <!-- Form Actions -->
                        <div class="flex justify-end pt-4 border-t border-gray-200 dark:border-gray-600">
                            @if(!empty($items))
                                <x-button 
                                    type="submit" 
                                    variant="primary"
                                >
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    {{ $editValue ? 'Update Order' : 'Create Order' }}
                                </x-button>
                            @endif
                        </div>
                    </form>
        </x-collapsible-card>
        @endif
        
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
            
        @if (session()->has('errorediting'))          
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-2 rounded mb-4">
                {{ session('errorediting') }}
                <p>Reasons</p>
                <ul>
                    <li>Data Integrity – Prevents tampering with approved or invoiced data. </li>              
                    <li>Return Accuracy – Sales returns rely on original prices and quantities.</li>    
                    <li>Audit Compliance – You need a consistent history of what was ordered and approved.</li>    
                    <li>Inventory Accuracy – Editing after approval may desync stock levels.</li>    
                    <li>Customer Trust – Modifying orders post-approval can cause billing issues.</li>    
                </ul>
            </div>
        @endif

        <!-- Sales Orders List Card -->
        @if (
            auth()->user()->can(\App\Enums\Enum\PermissionEnum::EDIT_SALES_ORDER->value) ||
            auth()->user()->can(\App\Enums\Enum\PermissionEnum::VIEW_SALES_ORDER->value)
        )

        <x-collapsible-card title="Sales Orders List" open="true" size="full">
            <div class="flex items-center justify-between p-4 pr-10">
                <div class="flex items-start space-x-6">
                    <!-- Search Input -->
                    <div class="flex flex-col">
                        <x-input type="text" wire:model.live.debounce.300ms="search" name="search" label="Search" placeholder="Search sales orders..." class="w-64" />
                    </div>
                    
                    <!-- Status Filter -->
                    <div class="flex flex-col">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Status Filter</label>
                        <select wire:model.live="status" 
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:ring-blue-400 w-48">
                            @foreach($statusOptions as $value => $label)
                                <option value="{{ $value }}">{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
            <div class="overflow-x-auto rounded-lg border border-zinc-200 dark:border-zinc-700">
                <table class="min-w-full text-sm text-zinc-900 dark:text-zinc-100">
                    <thead class="text-sm text-zinc-700 dark:text-zinc-300 uppercase bg-zinc-50 dark:bg-zinc-700 sticky top-0 z-10">
                        <tr>
                            <th class="px-6 py-3 font-semibold text-left w-20 text-zinc-900 dark:text-zinc-100">QR Code</th>
                            <th class="px-6 py-3 font-semibold text-left w-32 text-zinc-900 dark:text-zinc-100">SO#</th>
                            <th class="px-6 py-3 font-semibold text-left w-40 text-zinc-900 dark:text-zinc-100">Customer</th>
                            <th class="px-6 py-3 font-semibold text-left w-24 text-zinc-900 dark:text-zinc-100">Status</th>
                            <th class="px-6 py-3 font-semibold text-left w-48 text-zinc-900 dark:text-zinc-100">Email</th>
                            <th class="px-6 py-3 font-semibold text-left w-48 text-zinc-900 dark:text-zinc-100">Shipping Address</th>
                            <th class="px-6 py-3 font-semibold text-left w-32 text-zinc-900 dark:text-zinc-100">Payment Method</th>
                            <th class="px-6 py-3 font-semibold text-left w-24 text-zinc-900 dark:text-zinc-100">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($data_results as $data)
                            <tr class="odd:bg-white odd:dark:bg-gray-900 even:bg-gray-50 even:dark:bg-gray-800 hover:bg-gray-100 dark:hover:bg-gray-600 transition-colors">
                                <td class="px-6 py-3 align-top w-20 text-gray-900 dark:text-gray-100">
                                    <button wire:click="showSalesOrdeQrCode('{{ $data->sales_order_number }}')" class="hover:opacity-80 transition-opacity">
                                        {!! QrCode::size(50)->generate($data->sales_order_number) !!}
                                    </button>
                                </td>
                                <td class="px-6 py-3 align-top w-32 whitespace-nowrap truncate text-gray-900 dark:text-gray-100 font-medium">
                                    {{ ucfirst($data->sales_order_number) }}
                                </td>
                                <td class="px-6 py-3 align-top w-40 whitespace-nowrap truncate text-gray-900 dark:text-gray-100">
                                    {{ ucfirst($data->customer->name) }}
                                </td>
                                <td class="px-6 py-3 align-top w-24 text-gray-900 dark:text-gray-100">
                                    <span class="inline-block w-24 text-center px-3 py-1 rounded-full text-xs font-semibold
                                        @if($data->status === 'pending') bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200
                                        @elseif($data->status === 'approved') bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200
                                        @elseif($data->status === 'rejected') bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200
                                        @else bg-gray-100 text-gray-800 dark:bg-gray-600 dark:text-gray-100 @endif">
                                        {{ ucfirst($data->status) }}
                                    </span>
                                </td>
                                <td class="px-6 py-3 align-top w-48 whitespace-nowrap truncate text-gray-900 dark:text-gray-100">
                                    {{ $data->email }}
                                </td>
                                <td class="px-6 py-3 align-top w-48 text-gray-900 dark:text-gray-100">
                                    <div class="max-w-xs">
                                        <span class="truncate block">{{ $data->shipping_address }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-3 align-top w-32 whitespace-nowrap truncate text-gray-900 dark:text-gray-100">
                                    {{ $shippingMethodDropDown[$data->shipping_method] ?? 'N/A' }}
                                </td>
                                <td class="px-6 py-3 align-top w-24 text-gray-900 dark:text-gray-100">
                                    <div class="flex space-x-2">
                                        @if(auth()->user()->can(\App\Enums\Enum\PermissionEnum::EDIT_SALES_ORDER->value))
                                            <x-button type="button" wire:click="edit({{ $data->id }})" variant="warning" size="sm">Edit</x-button>
                                        @endif
                                        @if(auth()->user()->can(\App\Enums\Enum\PermissionEnum::VIEW_SALES_ORDER->value))
                                            <x-button type="button" href="{{ route('salesorder.view',$data->id) }}" variant="primary" size="sm">View</x-button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="px-4 py-12 text-center text-zinc-400 dark:text-zinc-500">
                                    <div class="flex flex-col items-center justify-center">
                                        <svg class="w-12 h-12 mb-2 text-zinc-300 dark:text-zinc-600" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 20h.01M12 4a8 8 0 100 16 8 8 0 000-16z" /></svg>
                                        <span class="text-lg mb-2">No sales orders found</span>
                                        <span class="text-sm">Sales orders will appear here once created.</span>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="py-4 px-3">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-4">
                        <label class="text-sm font-medium text-gray-900 dark:text-white">Per Page:</label>
                        <select wire:model.live="perPage"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                            <option value="5">5</option>
                            <option value="10">10</option>
                            <option value="20">20</option>
                            <option value="50">50</option>
                            <option value="100">100</option>
                        </select>
                    </div>
                    <div>
                        {{ $data_results->links() }}
                    </div>
                </div>
            </div>
        </x-collapsible-card>
        @endif
    </div>
</div>