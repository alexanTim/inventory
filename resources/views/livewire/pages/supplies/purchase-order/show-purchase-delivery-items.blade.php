<x-slot:header>Purchase Order</x-slot:header>
<x-slot:subheader>Purchase Order Details with QR Code for Stock-In</x-slot:subheader>

<div class="mb-14">
    <div class="max-w-7xl mx-auto">       

        <!-- Purchase Order Details -->
        <x-collapsible-card title="Delivery Order Details" open="true" size="full">
            <div class="grid gap-6 mb-6 md:grid-cols-2 lg:grid-cols-3">
                <x-input
                  type="text" 
                  name="dr_number" 
                  value="{{ $deliveries->dr_number }}" 
                  label="Delivery Order #" 
                  disabled="true" 
                />

                <x-input 
                  type="text" 
                  name="purchase_order" 
                  value="PO #{{ $deliveries->purchaseOrder->po_num ?? 'N/A' }}"
                  label="Purchase Order" 
                  disabled="true" 
                />
               
               
            </div>
        </x-collapsible-card>      

        <!-- Ordered Items -->
        <x-collapsible-card title="Delivered Items" open="true" size="full">
            <div class="overflow-x-auto">
               
                <table class="mt-5 w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                    <thead class="text-sm text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                        <tr>
                            <th scope="col" class="px-6 py-3">Batch Number</th>
                            <th scope="col" class="px-6 py-3">Expiration Date</th>
                            <th scope="col" class="px-6 py-3">Manufactured Date</th>
                            <th scope="col" class="px-6 py-3">Initial Qty</th>     
                            <th scope="col" class="px-6 py-3">Current Qty</th>                      
                            <th scope="col" class="px-6 py-3">Unit Price</th>
                            <th scope="col" class="px-6 py-3 bg-green-200">Received Quantity</th>  
                            <th scope="col" class="px-6 py-3">Remaining Qty</th>
                            <th scope="col" class="px-6 py-3">Total Price</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                            $totalQuantity = 0;
                            $totalPrice = 0;
                        ?>
                        @forelse($deliveries->supplyBatches as $order)
                            <?php
                              $totalQuantity += $order->received_qty;
                              $totalPrice += $order->supplyOrder->unit_price *  $order->received_qty;
                            ?>

                            <tr class="odd:bg-white odd:dark:bg-gray-900 even:bg-gray-50 even:dark:bg-gray-800 border-b dark:border-gray-700">
                                <td class="px-6 py-4 font-mono">{{ $order->batch_number ?? 'N/A' }}</td>
                                <td class="px-6 py-4">{{ $order->expiration_date ?? 'N/A' }}</td>
                                <td class="px-6 py-4">{{ $order->manufactured_date ?? 'N/A' }}</td>
                                <td class="px-6 py-4">{{$order->initial_qty ?? 'N/A' }}</td>                               
                                <td class="px-6 py-4">{{$order->current_qty ?? 'N/A' }}</td>  
                                <td class="px-6 py-4">{{$order->supplyOrder->unit_price ?? 'N/A' }}</td>     
                                <td class="px-6 py-4 bg-green-200">{{ $order->received_qty }}</td>
                                <td class="px-6 py-4">{{ $order->current_qty  - $order->received_qty }}</td>
                                <td class="px-6 py-4 font-semibold">₱{{ number_format($order->supplyOrder->unit_price *  $order->received_qty , 2) }}</td>
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
                     {{-- @if($purchaseOrder->status === 'pending')
                        <a href="{{ route('supplies.PurchaseOrder.showForApproval', ['Id' => $purchaseOrder->id]) }}"
                            class="inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-blue-600 border border-transparent rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            Go to Approval
                        </a>
                    @endif
                    <a href="/Product/PurchaseOrder" 
                        class="inline-flex items-center px-6 py-2.5 text-sm font-medium text-white bg-blue-600 border border-transparent rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                        Back to Purchase Orders
                    </a> --}}
                </div>
            </div>
        </div>
    </div>
</div>
