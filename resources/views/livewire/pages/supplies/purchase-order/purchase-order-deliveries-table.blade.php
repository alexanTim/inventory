
<div>
    <div>                 
        <x-collapsible-card 
          title="Delivery Order List" 
          open="true" 
          size="full"
          >
            <div class="flex items-center justify-between p-4 pr-10">
                <div class="flex items-start space-x-6 w-full">
                    <!-- Search Input -->
                    <div class="flex flex-col">
                        <x-input type="text" wire:model.live.debounce.300ms="search" name="search" label="Search" placeholder="Search delivery orders..." class="w-64" />
                    </div>                   
                </div>
            </div>
            <div class="overflow-x-auto rounded-lg border border-zinc-200 dark:border-zinc-700">
                         
             
              
              <table class="min-w-full text-sm text-zinc-900 dark:text-zinc-100">
                    <thead class="text-sm text-zinc-700 dark:text-zinc-300 uppercase bg-zinc-50 dark:bg-zinc-700 sticky top-0 z-10">
                        <tr>
                            <th class="px-6 py-3 font-semibold text-left w-32 text-zinc-900 dark:text-zinc-100">Delivery #</th>   
                            <th class="px-6 py-3 font-semibold text-left w-32 text-zinc-900 dark:text-zinc-100">PO #</th> 
                            <th class="px-6 py-3 font-semibold text-left w-40 text-zinc-900 dark:text-zinc-100">Action</th>                                                 
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($deliveries as $delivery)
                            <tr class="odd:bg-white odd:dark:bg-gray-900 even:bg-gray-50 even:dark:bg-gray-800 hover:bg-gray-100 dark:hover:bg-gray-600 transition-colors">                              
                                <td class="px-6 py-3 align-top w-48 whitespace-nowrap truncate text-gray-900 dark:text-gray-100">
                                    {{ $delivery->dr_number }}
                                </td>
                                <td class="px-6 py-3 align-top w-48 whitespace-nowrap truncate text-gray-900 dark:text-gray-100">
                                     {{ $delivery->purchaseOrder->po_num ?? '-' }}
                                </td>
                               
                                <td class="px-6 py-3 align-top w-48 whitespace-nowrap truncate text-gray-900 dark:text-gray-100">
                                    <x-button 
                                      href="{{ route('supplies.DeliveryOrder.view', ['Id' => $delivery->id]) }}"
                                      class="" variant="secondary">
                                      View 
                                    </x-button>
                                     <x-button 
                                      href="{{ route('supplies.DeliveryOrder.view', ['Id' => $delivery->id]) }}"
                                      class="" variant="danger">
                                      Delete 
                                    </x-button>
                                </td>                                
                            </tr>                         
                        @endforeach                            
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
                         {{ $deliveries->links() }}
                    </div>
                </div>
            </div>
        </x-collapsible-card>      
    </div>
</div>