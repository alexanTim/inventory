<x-slot:header>Purchase Order</x-slot:header>
<x-slot:subheader>Purchase Order Details with QR Code for Stock-In</x-slot:subheader>

<div class="mb-14">
    <div class="max-w-7xl mx-auto">
        <!-- QR Code Section - Prominently Displayed -->
        <div class="mb-8">
            <x-collapsible-card title="QR Code for Stock-In Scanning" open="true" size="full">
                <div class="flex flex-col items-center justify-center p-8 bg-gradient-to-br from-blue-50 to-indigo-100 dark:from-blue-900/20 dark:to-indigo-900/20 rounded-xl">
                    <div class="text-center mb-6">
                        <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-2">
                            Scan This QR Code for Stock-In
                        </h3>
                        <p class="text-gray-600 dark:text-gray-400">
                            Use this QR code in the Stock-In module to process receiving for this Purchase Order
                        </p>
                    </div>
                    
                    <!-- Large QR Code -->
                    <div class="bg-white p-8 rounded-2xl shadow-lg border-4 border-blue-200 dark:border-blue-700">
                        {!! QrCode::size(300)->generate(json_encode([
                            'type' => 'purchase_order',
                            'po_num' => $purchaseOrder->po_num,
                            'status' => $purchaseOrder->status,
                            'supplier' => $purchaseOrder->supplier ? $purchaseOrder->supplier->name : 'N/A',
                            'department' => $purchaseOrder->department ? $purchaseOrder->department->name : 'N/A',
                            'total_qty' => $purchaseOrder->total_qty,
                            'total_price' => $purchaseOrder->total_price,
                            'order_date' => $purchaseOrder->order_date->format('Y-m-d'),
                            'system' => 'Gentle Walker PO System'
                        ])) !!}
                    </div>
                    
                    <!-- PO Number Display -->
                    <div class="mt-6 text-center">
                        <p class="text-3xl font-mono font-bold text-blue-600 dark:text-blue-400">
                            {{ $purchaseOrder->po_num }}
                        </p>
                        <p class="text-sm text-gray-500 dark:text-gray-400 mt-2">
                            Purchase Order Number
                        </p>
                    </div>
                    
                    <!-- Status Badge -->
                    <div class="mt-4">
                        <span class="inline-flex items-center px-4 py-2 text-sm font-semibold rounded-full
                            @if ($purchaseOrder->status === 'for_approval') bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300
                            @elseif($purchaseOrder->status === 'to_receive') bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300
                            @elseif($purchaseOrder->status === 'delivered') bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300
                            @elseif($purchaseOrder->status === 'received') bg-emerald-100 text-emerald-800 dark:bg-emerald-900 dark:text-emerald-300
                            @elseif($purchaseOrder->status === 'rejected') bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300
                            @else bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-300 @endif">
                            {{ str_replace('_', ' ', ucfirst($purchaseOrder->status)) }}
                        </span>
                    </div>
                </div>
            </x-collapsible-card>
        </div>

        <!-- Purchase Order Details -->
        <x-collapsible-card title="Purchase Order Details" open="true" size="full">
            <div class="grid gap-6 mb-6 md:grid-cols-2 lg:grid-cols-3">
                <x-input type="text" name="po_num" value="{{ $purchaseOrder->po_num }}" label="PO Number" disabled="true" />
                <x-input type="text" name="status" value="{{ str_replace('_', ' ', ucfirst($purchaseOrder->status)) }}" label="Status" disabled="true" />
                <x-input type="text" name="ordered_by" value="{{ $purchaseOrder->orderedBy ? $purchaseOrder->orderedBy->name : 'N/A' }}" label="Ordered By" disabled="true" />
                <x-input type="text" name="supplier" value="{{ $purchaseOrder->supplier->name ?? 'N/A' }}" label="Supplier" disabled="true" />
                <x-input type="text" name="receiving_department" value="{{ str_replace('_', ' ', ucfirst($purchaseOrder->department->name ?? 'N/A')) }}" label="Receiving Department" disabled="true" />
                <x-input type="text" name="order_date" value="{{ $purchaseOrder->order_date->format('M d, Y') }}" label="Order Date" disabled="true" />

                <x-input type="text" name="loaded_date" value="{{  ( $purchaseOrder->loaded_date !='' )? $purchaseOrder->loaded_date->format('M d, Y') : ''}}" label="Loaded Date" disabled="true" />

                <x-input type="text" name="payment_terms" value="{{ $purchaseOrder->payment_terms }}" label="Payment Terms" disabled="true" />
                <x-input type="text" name="quotation" value="{{ $purchaseOrder->quotation }}" label="Quotation" disabled="true" />
                <x-input type="text" name="approver" value="{{ $purchaseOrder->approver ? $purchaseOrder->approverInfo->name : 'N/A' }}" label="Approver" disabled="true" />
            </div>
        </x-collapsible-card>

        <!-- Order Summary -->
        <x-collapsible-card title="Order Summary" open="true" size="full">
            <div class="grid gap-6 mb-6 md:grid-cols-3">
                <div class="bg-blue-50 dark:bg-blue-900/20 p-6 rounded-lg border border-blue-200 dark:border-blue-700">
                    <div class="text-center">
                        <p class="text-sm font-medium text-blue-600 dark:text-blue-400">Total Quantity</p>
                        <p class="text-3xl font-bold text-blue-900 dark:text-blue-100">{{ number_format($totalQuantity, 2) }}</p>
                    </div>
                </div>
                <div class="bg-green-50 dark:bg-green-900/20 p-6 rounded-lg border border-green-200 dark:border-green-700">
                    <div class="text-center">
                        <p class="text-sm font-medium text-green-600 dark:text-green-400">Total Price</p>
                        <p class="text-3xl font-bold text-green-900 dark:text-green-100">₱{{ number_format($totalPrice, 2) }}</p>
                    </div>
                </div>
                <div class="bg-purple-50 dark:bg-purple-900/20 p-6 rounded-lg border border-purple-200 dark:border-purple-700">
                    <div class="text-center">
                        <p class="text-sm font-medium text-purple-600 dark:text-purple-400">Items Count</p>
                        <p class="text-3xl font-bold text-purple-900 dark:text-purple-100">{{ $purchaseOrder->supplyOrders->count() }}</p>
                    </div>
                </div>
            </div>
        </x-collapsible-card>

        <!-- Ordered Items -->
        <x-collapsible-card title="Ordered Items" open="true" size="full">
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                    <thead class="text-sm text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                        <tr>
                            <th scope="col" class="px-6 py-3">SKU</th>
                            <th scope="col" class="px-6 py-3">Description</th>
                            <th scope="col" class="px-6 py-3">Item Type</th>
                            <th scope="col" class="px-6 py-3">Unit Price</th>
                            <th scope="col" class="px-6 py-3">Quantity</th>
                            <th scope="col" class="px-6 py-3">Expected Quantity</th>
                            <th scope="col" class="px-6 py-3">Total Price</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($purchaseOrder->supplyOrders as $order)
                            <tr class="odd:bg-white odd:dark:bg-gray-900 even:bg-gray-50 even:dark:bg-gray-800 border-b dark:border-gray-700">
                                <td class="px-6 py-4 font-mono">{{ $order->supplyProfile->supply_sku ?? 'N/A' }}</td>
                                <td class="px-6 py-4">{{ $order->supplyProfile->supply_description ?? 'N/A' }}</td>
                                <td class="px-6 py-4">{{ $order->supplyProfile->itemType->name ?? 'N/A' }}</td>
                                <td class="px-6 py-4">₱{{ number_format($order->unit_price, 2) }}</td>
                                <td class="px-6 py-4">{{ number_format($order->order_qty, 2) }}</td>
                                <td class="px-6 py-4">{{ number_format($order->expected_qty, 2) }}</td>
                                <td class="px-6 py-4 font-semibold">₱{{ number_format($order->order_total_price, 2) }}</td>
                            </tr>
                        @empty
                            <tr class="odd:bg-white odd:dark:bg-gray-900 even:bg-gray-50 even:dark:bg-gray-800 border-b dark:border-gray-700">
                                <td colspan="6" class="px-6 py-4 text-center">No items found</td>
                            </tr>
                        @endforelse
                    </tbody>
                    <tfoot class="text-sm text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                        <tr>
                            <td colspan="4" class="px-6 py-3 text-right font-bold">Total:</td>
                            <td class="px-6 py-3 font-bold">{{ number_format($totalQuantity, 2) }}</td>
                            <td class="px-6 py-3 font-bold">₱{{ number_format($totalPrice, 2) }}</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </x-collapsible-card>

        <!-- Action Buttons -->
        <div class="fixed bottom-0 right-0 p-4 bg-white dark:bg-gray-800 border-t border-gray-200 dark:border-gray-700 w-full">
            <div class="flex justify-end items-center">
                {{-- <div class="flex space-x-4">
                    <a href="{{ route('supplies.PurchaseOrder') }}" 
                        class="text-gray-500 bg-white hover:bg-gray-100 focus:ring-4 focus:outline-none focus:ring-blue-300 rounded-lg border border-gray-200 text-sm font-medium px-5 py-2.5 hover:text-gray-900 focus:z-10 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-500 dark:hover:text-white dark:hover:bg-gray-600 dark:focus:ring-gray-600">
                        ← Back to List
                    </a>
                    
                    @if($purchaseOrder->status === 'pending')
                        <a href="{{ route('supplies.PurchaseOrder.showForApproval', ['Id' => $purchaseOrder->id]) }}"
                            class="inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-blue-600 border border-transparent rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            Go to Approval
                        </a>
                    @endif
                </div> --}}
                
                <div class="flex space-x-4">
                     @if($purchaseOrder->status === 'pending')
                        @if( $purchaseOrder->loaded_date !='')
                        <a href="{{ route('supplies.PurchaseOrder.showForApproval', ['Id' => $purchaseOrder->id]) }}"
                            class="inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-blue-600 border border-transparent rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            Go to Approval
                        </a>
                        @else 
                        <p class="mt-2 text-sm text-red-500">Approve button is disabled/hidden until a Loaded Date is provided</p>
                       
                        @endif
                    @endif
                    <a href="/Product/PurchaseOrder" 
                        class="inline-flex items-center px-6 py-2.5 text-sm font-medium text-white bg-blue-600 border border-transparent rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                        Back to Purchase Orders
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
