<x-slot:header>Inventory</x-slot:header>
<x-slot:subheader>Product</x-slot:subheader>
<div class="">

    <div class="">
        @if(auth()->user()->can(\App\Enums\Enum\PermissionEnum::CREATE_INVENTORY->value))
        <x-collapsible-card title="Create Product Profile" open="false" size="full">
            <form wire:submit.prevent="create" x-show="open" x-transition>
                <!-- Basic Information Section -->
                <div class="mb-6">
                    <h4 class="text-lg font-medium text-zinc-900 dark:text-white mb-4">Basic Information</h4>
                    <div class="grid gap-4 md:grid-cols-3">
                        <x-input type="text" wire:model="supply_sku" name="supply_sku" label="SKU"
                            placeholder="Enter SKU" />
                        <x-dropdown wire:model="item_class_id" name="item_class_id" label="Item Class" 
                            :options="$itemClasses->pluck('name', 'id')" placeholder="Select Item Class" />
                        <x-dropdown wire:model="item_type_id" name="item_type_id" label="Item Type" :options="$itemTypes->pluck('name', 'id')"
                            placeholder="Select Item Type" />
                    </div>
                </div>

                <!-- Product Details Section -->
                <div class="mb-6">
                    <h4 class="text-lg font-medium text-zinc-900 dark:text-white mb-4">Product Details</h4>
                    <div class="grid gap-4 md:grid-cols-2">
                        <x-dropdown wire:model="allocation_id" name="allocation_id" label="Allocation" :options="$allocations->pluck('name', 'id')"
                            placeholder="Select Allocation" />
                        <x-dropdown wire:model="supply_uom" name="supply_uom" label="Unit Of Measure" 
                            :options="$uomOptions" placeholder="Select unit of measure" />
                    </div>
                    <div class="mt-4">
                        <x-input type="text" wire:model="supply_description" name="supply_description"
                            label="Description" placeholder="Enter product description" />
                    </div>
                </div>

                <!-- Inventory Management Section -->
                <div class="mb-6">
                    <h4 class="text-lg font-medium text-zinc-900 dark:text-white mb-4">Inventory Management</h4>
                    <div class="grid gap-4 md:grid-cols-2">
                        <x-input type="number" step="1" wire:model="supply_qty" name="supply_qty" label="Current Quantity"
                            placeholder="0" readonly />
                        <div>
                            <x-input type="number" step="1" wire:model="low_stock_threshold_percentage" name="low_stock_threshold_percentage"
                                label="Low Stock Threshold (%)" placeholder="Enter threshold (e.g., 20)" />
                            <p class="text-xs text-zinc-500 dark:text-zinc-400 mt-1">Percentage to trigger low stock warning</p>
                        </div>
                    </div>
                </div>

                <!-- Pricing Section -->
                <div class="mb-6">
                    <h4 class="text-lg font-medium text-zinc-900 dark:text-white mb-4">Pricing</h4>
                    <div class="grid gap-4 md:grid-cols-4">
                        <x-input type="number" step="0.01" wire:model="unit_cost" name="unit_cost"
                            label="Unit Cost" placeholder="Enter unit cost" />
                        <x-input type="number" step="0.01" wire:model="supply_price1" name="supply_price1"
                            label="Price Tier 1" placeholder="Enter price 1" />
                        <x-input type="number" step="0.01" wire:model="supply_price2" name="supply_price2"
                            label="Price Tier 2" placeholder="Enter price 2 (optional)" />
                        <x-input type="number" step="0.01" wire:model="supply_price3" name="supply_price3"
                            label="Price Tier 3" placeholder="Enter price 3 (optional)" />
                    </div>
                    <div class="mt-2 p-3 bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg">
                        <p class="text-sm text-blue-700 dark:text-blue-300">
                            <strong>Unit Cost:</strong> The cost to purchase this item from suppliers (used in purchase orders)<br>
                            <strong>Price Tiers:</strong> Selling prices to customers (used in sales)
                        </p>
                    </div>
                </div>

                <div class="flex justify-end pt-4 border-t border-zinc-200 dark:border-zinc-600">
                    <x-button type="submit" variant="primary">Create Product Profile</x-button>
                </div>
            </form>
        </x-collapsible-card>
        @endif
        @if (session('message'))
            <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)"
                class="transition duration-500 ease-in-out" x-transition>
                <x-flash-message />
            </div>
        @endif
        <x-collapsible-card title="Product List" open="true" size="full">
            <div class="flex items-center justify-between p-4 pr-10">
                <div class="flex items-start space-x-6">
                    <!-- Search Input -->
                    <div class="flex flex-col">
                        <x-input type="text" wire:model.live="search" name="search" label="Search" placeholder="Search product..." class="w-56" />
                    </div>
                    <!-- Item Class Filter -->
                    <div class="flex flex-col">
                        <x-dropdown name="itemClassFilter" label="Item Class" :options="['' => 'All Item Classes'] + $itemClasses->pluck('name', 'id')->toArray()" wire:model.live="itemClassFilter" />
                    </div>
                    <!-- Item Type Filter -->
                    <div class="flex flex-col">
                        <x-dropdown name="itemTypeFilter" label="Item Type" :options="['' => 'All Item Types'] + $itemTypes->pluck('name', 'id')->toArray()" wire:model.live="itemTypeFilter" />
                    </div>
                    <!-- Allocation Filter -->
                    <div class="flex flex-col">
                        <x-dropdown name="allocationFilter" label="Allocation" :options="['' => 'All Allocations'] + $allocations->pluck('name', 'id')->toArray()" wire:model.live="allocationFilter" />
                    </div>
                    <!-- Low Stock Filter -->
                    <div class="flex flex-col">
                        <x-dropdown name="lowStockFilter" label="Stock Status" :options="['' => 'All Stock Levels', 'low_stock' => 'Low Stock (≤10)', 'critical_stock' => 'Critical Stock (≤5)', 'out_of_stock' => 'Out of Stock (0)', 'healthy_stock' => 'Healthy Stock (>10)']" wire:model.live="lowStockFilter" />
                    </div>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left rtl:text-right text-zinc-500 dark:text-zinc-400">
                    <thead class="text-sm text-zinc-700 uppercase bg-zinc-50 dark:bg-zinc-700 dark:text-zinc-400">
                        <tr>
                            <th scope="col" class="px-4 py-3">
                                Product
                            </th>
                            <th scope="col" class="px-4 py-3">
                                Type & Class
                            </th>
                            <th scope="col" class="px-4 py-3">
                                Stock Level
                            </th>
                            <th scope="col" class="px-4 py-3">
                                Pricing
                            </th>
                            <th scope="col" class="px-4 py-3">
                                Actions
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($supplies as $supply)
                            <tr
                                class="odd:bg-white odd:dark:bg-zinc-900 even:bg-zinc-50 even:dark:bg-zinc-800 border-b dark:border-zinc-700 border-zinc-200 hover:bg-zinc-100 dark:hover:bg-zinc-700">
                                
                                <!-- Product Column -->
                                <td class="px-4 py-4">
                                    <div class="flex items-start space-x-3">
                                        <!-- QR Code -->
                                        <button type="button" 
                                            wire:click.stop="showQrCode({{ $supply->id }})"
                                            data-action="qr-code"
                                            data-supply-id="{{ $supply->id }}"
                                            class="flex-shrink-0 cursor-pointer hover:opacity-80 transition-opacity"
                                            title="Click to view detailed QR code">
                                            {!! QrCode::size(50)->generate(json_encode([
                                                'sku' => $supply->supply_sku,
                                                'desc' => substr($supply->supply_description, 0, 50),
                                                'stock' => $supply->supply_qty,
                                                'uom' => $supply->supply_uom,
                                                'min' => $supply->supply_min_qty,
                                                'status' => ($supply->supply_qty <= 0 ? 'out_of_stock' : ($supply->supply_qty <= 5 ? 'critical_stock' : ($supply->supply_qty <= 10 ? 'low_stock' : 'healthy_stock')))
                                            ])) !!}
                                        </button>
                                        
                                        <!-- Product Info -->
                                        <div class="flex-1 min-w-0">
                                            <div class="flex items-center space-x-2">
                                                <p class="text-sm font-semibold text-zinc-900 dark:text-white truncate">
                                                    {{ $supply->supply_description }}
                                                </p>
                                                @if($supply->isLowStock())
                                                    <span class="inline-flex items-center px-2 py-1 text-xs font-medium bg-red-100 text-red-800 rounded-full dark:bg-red-900 dark:text-red-300">
                                                        <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                                        </svg>
                                                        Low Stock
                                                    </span>
                                                @endif
                                            </div>
                                            <p class="text-xs text-zinc-500 dark:text-zinc-400 mt-1">
                                                SKU: {{ $supply->supply_sku }}
                                            </p>
                                            <p class="text-xs text-zinc-500 dark:text-zinc-400">
                                                {{ $supply->allocation->name }}
                                            </p>
                                        </div>
                                    </div>
                                </td>

                                <!-- Type & Class Column -->
                                <td class="px-4 py-4">
                                    <div class="space-y-2">
                                        <div>
                                            <span class="text-sm font-medium text-zinc-900 dark:text-white">
                                                {{ $supply->itemType->name }}
                                            </span>
                                        </div>
                                        <div>
                                            @if($supply->itemClass && $supply->itemClass->name === 'consumable')
                                                <span class="inline-flex items-center px-2 py-1 text-xs font-medium bg-green-100 text-green-800 rounded-full dark:bg-green-900 dark:text-green-300">
                                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012 2v2M7 7h10"></path>
                                                    </svg>
                                                    Consumable
                                                </span>
                                            @elseif($supply->itemClass && $supply->itemClass->name === 'accessories')
                                                <span class="inline-flex items-center px-2 py-1 text-xs font-medium bg-blue-100 text-blue-800 rounded-full dark:bg-blue-900 dark:text-blue-300">
                                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                                    </svg>
                                                                                                            {{ $supply->itemClass->name }}
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </td>

                                <!-- Stock Level Column -->
                                <td class="px-4 py-4">
                                    <div class="space-y-2">
                                        <!-- Main quantity -->
                                        <div class="flex items-center space-x-2">
                                            <span class="text-lg font-bold text-zinc-900 dark:text-white">
                                                {{ number_format($supply->supply_qty, 0) }}
                                            </span>
                                            <span class="text-sm text-zinc-500 dark:text-zinc-400">
                                                {{ $supply->supply_uom }}
                                            </span>
                                        </div>
                                        
                                        <!-- Stock Status Badge -->
                                        <div class="mt-2">
                                            @if($supply->supply_qty <= 0)
                                                <span class="inline-flex items-center px-2 py-1 text-xs font-medium bg-red-100 text-red-800 rounded-full dark:bg-red-900 dark:text-red-300">
                                                    <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                                    </svg>
                                                    Out of Stock
                                                </span>
                                            @elseif($supply->supply_qty <= 5)
                                                <span class="inline-flex items-center px-2 py-1 text-xs font-medium bg-orange-100 text-orange-800 rounded-full dark:bg-orange-900 dark:text-orange-300">
                                                    <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                                    </svg>
                                                    Critical Stock
                                                </span>
                                            @elseif($supply->supply_qty <= 10)
                                                <span class="inline-flex items-center px-2 py-1 text-xs font-medium bg-yellow-100 text-yellow-800 rounded-full dark:bg-yellow-900 dark:text-yellow-300">
                                                    <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                                    </svg>
                                                    Low Stock
                                                </span>
                                            @else
                                                <span class="inline-flex items-center px-2 py-1 text-xs font-medium bg-green-100 text-green-800 rounded-full dark:bg-green-900 dark:text-green-300">
                                                    <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                                    </svg>
                                                    In Stock
                                                </span>
                                            @endif
                                        </div>
                                        
                                        <!-- Low stock threshold indicator -->
                                        <div class="text-xs text-zinc-500 dark:text-zinc-400 mt-1">
                                            Threshold: {{ $supply->low_stock_threshold_percentage }}%
                                        </div>
                                        
                                        <!-- Batch information for consumable items -->
                                        @if($supply->itemClass && $supply->itemClass->name === 'consumable')
                                            <div class="text-xs space-y-1">
                                                @if(isset($supply->total_batch_qty))
                                                    <div class="flex items-center gap-1 text-blue-600 dark:text-blue-400">
                                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012 2v2M7 7h10"></path>
                                                        </svg>
                                                        <span>{{ number_format($supply->total_batch_qty, 0) }} in batches</span>
                                                    </div>
                                                @endif
                                                
                                                <!-- Expiration alerts -->
                                                @if(isset($supply->expired_batches_count) && $supply->expired_batches_count > 0)
                                                    <div class="flex items-center gap-1 text-red-600">
                                                        <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                                        </svg>
                                                        <span>{{ $supply->expired_batches_count }} expired</span>
                                                    </div>
                                                @endif
                                                
                                                @if(isset($supply->expiring_soon_count) && $supply->expiring_soon_count > 0)
                                                    <div class="flex items-center gap-1 text-yellow-600">
                                                        <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                                        </svg>
                                                        <span>{{ $supply->expiring_soon_count }} expiring soon</span>
                                                    </div>
                                                @endif
                                                
                                                @if(isset($supply->next_expiry) && $supply->next_expiry)
                                                    <div class="flex items-center gap-1 text-zinc-500 dark:text-zinc-400">
                                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                        </svg>
                                                        <span>Next: {{ $supply->next_expiry->format('M d, Y') }}</span>
                                                    </div>
                                                @endif
                                            </div>
                                        @endif
                                    </div>
                                </td>

                                <!-- Pricing Column -->
                                <td class="px-4 py-4">
                                    <div class="relative group">
                                        <button type="button" class="text-left w-full">
                                            <div class="text-sm font-semibold text-zinc-900 dark:text-white">
                                                ₱{{ number_format($supply->supply_price1, 2) }}
                                            </div>
                                            <div class="text-xs text-zinc-500 dark:text-zinc-400">
                                                {{ $supply->supply_price2 > 0 ? '2 tiers available' : '1 tier' }}
                                            </div>
                                        </button>
                                        
                                        <!-- Price tiers tooltip -->
                                        <div class="absolute left-0 z-10 hidden group-hover:block w-48 bg-white dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-lg shadow-lg p-3">
                                            <div class="text-xs font-medium text-zinc-700 dark:text-zinc-300 mb-2">Price Tiers</div>
                                            <div class="space-y-1">
                                                <div class="flex justify-between">
                                                    <span class="text-xs text-zinc-600 dark:text-zinc-400">Tier 1:</span>
                                                    <span class="text-xs font-medium">₱{{ number_format($supply->supply_price1, 2) }}</span>
                                                </div>
                                                @if($supply->supply_price2 > 0)
                                                    <div class="flex justify-between">
                                                        <span class="text-xs text-zinc-600 dark:text-zinc-400">Tier 2:</span>
                                                        <span class="text-xs font-medium">₱{{ number_format($supply->supply_price2, 2) }}</span>
                                                    </div>
                                                @endif
                                                @if($supply->supply_price3 > 0)
                                                    <div class="flex justify-between">
                                                        <span class="text-xs text-zinc-600 dark:text-zinc-400">Tier 3:</span>
                                                        <span class="text-xs font-medium">₱{{ number_format($supply->supply_price3, 2) }}</span>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </td>

                                <!-- Actions Column -->
                                <td class="px-4 py-4">
                                    <div class="flex items-center space-x-1">
                                        <!-- Stocks Button (for products with batches) -->
                                        @if($supply->supplyBatches && $supply->supplyBatches->count() > 0)
                                            @can(\App\Enums\Enum\PermissionEnum::VIEW_INVENTORY->value)
                                            <button type="button"
                                                onclick="window.location='{{ route('supplies.inventory.stocks', $supply->id) }}'"
                                                class="inline-flex items-center px-2 py-1 text-xs font-medium text-blue-700 bg-blue-100 border border-blue-200 rounded hover:bg-blue-200 dark:bg-blue-900 dark:text-blue-300 dark:border-blue-700 dark:hover:bg-blue-800 transition-colors"
                                                title="View Stock Batches">
                                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012 2v2M7 7h10"></path>
                                                </svg>
                                                Batches ({{ $supply->supplyBatches->count() }})
                                            </button>
                                            @endcan
                                        @endif
                                        
                                        <!-- Actions Dropdown -->
                                        @if(auth()->user()->can(\App\Enums\Enum\PermissionEnum::DELETE_INVENTORY_ITEM->value) ||
                                            auth()->user()->can(\App\Enums\Enum\PermissionEnum::EDIT_INVENTORY_ITEM->value))
                                        <div class="relative" x-data="{ open: false }">
                                            <button @click="open = !open" 
                                                class="inline-flex items-center px-2 py-1 text-xs font-medium text-zinc-700 bg-zinc-100 border border-zinc-200 rounded hover:bg-zinc-200 dark:bg-zinc-700 dark:text-zinc-300 dark:border-zinc-600 dark:hover:bg-zinc-600 transition-colors"
                                                title="More actions">
                                                <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                                    <path d="M10 6a2 2 0 110-4 2 2 0 010 4zM10 12a2 2 0 110-4 2 2 0 010 4zM10 18a2 2 0 110-4 2 2 0 010 4z"></path>
                                                </svg>
                                            </button>
                                            
                                            <div x-show="open" @click.away="open = false" 
                                                class="absolute right-0 z-20 mt-1 w-32 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg shadow-lg">
                                                <div class="py-1">
                                                    @if(auth()->user()->can(\App\Enums\Enum\PermissionEnum::EDIT_INVENTORY_ITEM->value))
                                                    <button wire:click="edit({{ $supply->id }})" 
                                                        class="flex items-center w-full px-4 py-3 text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors duration-200">
                                                        <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                                        </svg>
                                                        Edit
                                                    </button>
                                                    @endif
                                                    @if(auth()->user()->can(\App\Enums\Enum\PermissionEnum::DELETE_INVENTORY_ITEM->value))
                                                    <button wire:click="confirmDelete({{ $supply->id }})" 
                                                        class="flex items-center w-full px-4 py-3 text-sm font-medium text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/20 transition-colors duration-200">
                                                        <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                        </svg>
                                                        Delete
                                                    </button>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div x-data="{ show: @entangle('showEditModal') }" 
                 x-show="show" 
                 x-cloak
                 @keydown.escape.window="show = false; $wire.cancel()"
                 class="fixed top-0 left-0 right-0 z-50 w-full p-4 overflow-x-hidden overflow-y-auto md:inset-0 h-[calc(100%-1rem)] max-h-full flex items-center justify-center">
                <!-- Backdrop -->
                <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" 
                     x-show="show" 
                     x-transition:enter="ease-out duration-300"
                     x-transition:enter-start="opacity-0"
                     x-transition:enter-end="opacity-100"
                     x-transition:leave="ease-in duration-200"
                     x-transition:leave-start="opacity-100"
                     x-transition:leave-end="opacity-0"
                     @click="show = false; $wire.cancel()"></div>
                
                <div class="relative w-full max-w-2xl max-h-full">
                    <div class="relative bg-white rounded-lg shadow dark:bg-gray-700" @click.stop>
                        <div class="flex items-start justify-between p-4 border-b rounded-t dark:border-gray-600">
                            <h3 class="text-xl font-semibold text-zinc-900 dark:text-white">
                                Edit Supply Profile
                            </h3>
                            <button type="button"
                                class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ml-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white"
                                wire:click="cancel">
                                <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                    fill="none" viewBox="0 0 14 14">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                        stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                                </svg>
                                <span class="sr-only">Close modal</span>
                            </button>
                        </div>
                        <div class="p-6 space-y-6">
                            <!-- Basic Information Section -->
                            <div class="p-6 border border-gray-200 dark:border-gray-600 rounded-lg bg-gray-50 dark:bg-gray-800 mb-6">
                                <div class="flex items-center mb-6">
                                    <div class="flex items-center justify-center w-10 h-10 bg-blue-100 dark:bg-blue-900 rounded-full mr-4">
                                        <svg class="w-5 h-5 text-blue-600 dark:text-blue-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                    </div>
                                    <h4 class="text-xl font-semibold text-zinc-900 dark:text-white">Basic Information</h4>
                                </div>
                                <div class="space-y-6">
                                    <div class="grid gap-6 md:grid-cols-2">
                                        <x-input type="text" wire:model="supply_sku" name="supply_sku"
                                            label="SKU" placeholder="Enter SKU" />
                                        <x-dropdown wire:model="item_class_filter" name="item_class_filter"
                                            label="Item Class" :options="[
                                                'consumable' => 'Consumable',
                                                'accessories' => 'Accessories',
                                            ]" placeholder="Select Item Class" />
                                    </div>
                                    <div class="grid gap-6 md:grid-cols-2">
                                        <x-dropdown wire:model="item_type_id" name="item_type_id" label="Item Type"
                                            :options="$itemTypes->pluck('name', 'id')" placeholder="Select Item Type" />
                                        <x-dropdown wire:model="allocation_id" name="allocation_id" label="Allocation"
                                            :options="$allocations->pluck('name', 'id')" placeholder="Select Allocation" />
                                    </div>
                                    <div class="grid gap-6 md:grid-cols-2">
                                        <x-input type="text" wire:model="supply_description" name="supply_description"
                                            label="Description" placeholder="Enter product description" />
                                        <x-dropdown wire:model="supply_uom" name="supply_uom" label="Unit Of Measure" 
                                            :options="$uomOptions" placeholder="Select unit of measure" />
                                    </div>
                                </div>
                            </div>

                            <!-- Inventory Management Section -->
                            <div class="p-6 border border-gray-200 dark:border-gray-600 rounded-lg bg-green-50 dark:bg-gray-800 mb-6">
                                <div class="flex items-center mb-6">
                                    <div class="flex items-center justify-center w-10 h-10 bg-green-100 dark:bg-green-900 rounded-full mr-4">
                                        <svg class="w-5 h-5 text-green-600 dark:text-green-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                                        </svg>
                                    </div>
                                    <h4 class="text-xl font-semibold text-zinc-900 dark:text-white">Inventory Management</h4>
                                </div>
                                <div class="space-y-6">
                                    <div class="grid gap-6 md:grid-cols-2">
                                        <x-input type="number" step="1" wire:model="supply_qty" name="supply_qty"
                                            label="Current Quantity" placeholder="Enter quantity" class="dark:text-white" />
                                        <div>
                                            <x-input type="number" step="1" wire:model="low_stock_threshold_percentage"
                                                name="low_stock_threshold_percentage" label="Low Stock Threshold (%)"
                                                placeholder="Enter threshold" class="dark:text-white" />
                                            <p class="text-xs text-gray-700 dark:text-gray-200 mt-2">Alert when stock falls below this percentage</p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Pricing Tiers Section -->
                            <div class="p-6 border border-gray-200 dark:border-gray-600 rounded-lg bg-yellow-50 dark:bg-yellow-900/20">
                                <div class="flex items-center mb-6">
                                    <div class="flex items-center justify-center w-10 h-10 bg-yellow-100 dark:bg-yellow-900 rounded-full mr-4">
                                        <svg class="w-5 h-5 text-yellow-600 dark:text-yellow-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                                        </svg>
                                    </div>
                                    <h4 class="text-xl font-semibold text-zinc-900 dark:text-white">Pricing</h4>
                                </div>
                                <div class="space-y-6">
                                    <div class="grid gap-6 md:grid-cols-4">
                                        <x-input type="number" step="0.01" wire:model="unit_cost"
                                            name="unit_cost" label="Unit Cost" placeholder="Enter unit cost" />
                                        <x-input type="number" step="0.01" wire:model="supply_price1"
                                            name="supply_price1" label="Price Tier 1" placeholder="Enter price 1" />
                                        <x-input type="number" step="0.01" wire:model="supply_price2"
                                            name="supply_price2" label="Price Tier 2" placeholder="Enter price 2 (optional)" />
                                        <x-input type="number" step="0.01" wire:model="supply_price3"
                                            name="supply_price3" label="Price Tier 3" placeholder="Enter price 3 (optional)" />
                                    </div>
                                    <div class="p-3 bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg">
                                        <p class="text-sm text-blue-700 dark:text-blue-300">
                                            <strong>Unit Cost:</strong> The cost to purchase this item from suppliers (used in purchase orders)<br>
                                            <strong>Price Tiers:</strong> Selling prices to customers (used in sales)
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div
                            class="flex items-center p-6 space-x-2 border-t border-gray-200 rounded-b dark:border-gray-600">
                            <x-button type="button" wire:click="{{ $editingSupplyId ? 'update' : 'create' }}"
                                variant="primary">{{ $editingSupplyId ? 'Save changes' : 'Submit' }}</x-button>
                            <x-button type="button" wire:click="cancel" variant="secondary">Cancel</x-button>
                        </div>
                    </div>
                </div>
            </div>

            <div x-data="{ show: @entangle('showDeleteModal') }" 
                 x-show="show" 
                 x-cloak
                 @keydown.escape.window="show = false; $wire.cancel()"
                 class="fixed top-0 left-0 right-0 z-50 w-full p-4 overflow-x-hidden overflow-y-auto md:inset-0 h-[calc(100%-1rem)] max-h-full flex items-center justify-center">
                <!-- Backdrop -->
                <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" 
                     x-show="show" 
                     x-transition:enter="ease-out duration-300"
                     x-transition:enter-start="opacity-0"
                     x-transition:enter-end="opacity-100"
                     x-transition:leave="ease-in duration-200"
                     x-transition:leave-start="opacity-100"
                     x-transition:leave-end="opacity-0"
                     @click="show = false; $wire.cancel()"></div>
                
                <div class="relative w-full max-w-md max-h-full">
                    <div class="relative bg-white rounded-lg shadow dark:bg-gray-700" @click.stop>
                        <button type="button"
                            class="absolute top-3 right-2.5 text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ml-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white"
                            wire:click="cancel">
                            <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                fill="none" viewBox="0 0 14 14">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                    stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                            </svg>
                            <span class="sr-only">Close modal</span>
                        </button>
                        <div class="p-6 text-center">
                            <svg class="mx-auto mb-4 text-gray-400 w-12 h-12 dark:text-gray-200" aria-hidden="true"
                                xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                    stroke-width="2" d="M10 11V6m0 8h.01M19 10a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                            </svg>
                            <h3 class="mb-5 text-lg font-normal text-zinc-500 dark:text-zinc-400">Are you sure you want
                                to delete this supply profile?</h3>
                            <div class="flex justify-center space-x-2">
                                <x-button type="button" wire:click="delete" variant="danger">Yes, I'm
                                    sure</x-button>
                                <x-button type="button" wire:click="cancel" variant="secondary">No,
                                    cancel</x-button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- QR Code Modal -->
            <div x-data="{ show: @entangle('showQrModal') }" 
                 x-show="show" 
                 x-cloak
                 @keydown.escape.window="show = false; $wire.closeQrModal()"
                 x-transition:enter="ease-out duration-200"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 x-transition:leave="ease-in duration-150"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"
                 class="fixed top-0 left-0 right-0 z-50 w-full p-4 overflow-x-hidden overflow-y-auto md:inset-0 h-[calc(100%-1rem)] max-h-full flex items-center justify-center">
                <!-- Backdrop -->
                <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" 
                     x-show="show" 
                     x-transition:enter="ease-out duration-200"
                     x-transition:enter-start="opacity-0"
                     x-transition:enter-end="opacity-100"
                     x-transition:leave="ease-in duration-150"
                     x-transition:leave-start="opacity-100"
                     x-transition:leave-end="opacity-0"
                     @click="show = false; $wire.closeQrModal()"></div>
                
                <div class="relative w-full max-w-2xl max-h-full">
                    <div class="relative bg-white rounded-lg shadow dark:bg-gray-700" @click.stop x-show="show && $wire.selectedSupply">
                        <div class="flex items-start justify-between p-4 border-b rounded-t dark:border-gray-600">
                            <h3 class="text-xl font-semibold text-zinc-900 dark:text-white">
                                QR Code Details
                            </h3>
                            <button type="button"
                                class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ml-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white"
                                @click="show = false; $wire.closeQrModal()">
                                <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                    fill="none" viewBox="0 0 14 14">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                        stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                                </svg>
                                <span class="sr-only">Close modal</span>
                            </button>
                        </div>
                        <div class="p-6">
                            @if($selectedSupply)
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- QR Code Section -->
                                <div class="flex flex-col items-center justify-center p-6 bg-gray-50 dark:bg-gray-800 rounded-lg">
                                    <h4 class="text-lg font-semibold text-zinc-900 dark:text-white mb-4">QR Code</h4>
                                    <div class="bg-white p-4 rounded-lg shadow-sm">
                                        {!! QrCode::size(200)->generate(json_encode([
                                            'sku' => $selectedSupply->supply_sku,
                                            'desc' => substr($selectedSupply->supply_description, 0, 50),
                                            'stock' => $selectedSupply->supply_qty,
                                            'uom' => $selectedSupply->supply_uom,
                                            'min' => $selectedSupply->supply_min_qty,
                                            'status' => ($selectedSupply->supply_qty <= 0 ? 'out_of_stock' : ($selectedSupply->supply_qty <= 5 ? 'critical_stock' : ($selectedSupply->supply_qty <= 10 ? 'low_stock' : 'healthy_stock')))
                                        ])) !!}
                                    </div>
                                    <p class="text-sm text-gray-600 dark:text-gray-400 mt-2 font-mono">{{ $selectedSupply->supply_sku }}</p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1 text-center">Scan for inventory tracking</p>
                                </div>
                                
                                <!-- Product Details Section -->
                                <div class="space-y-4">
                                    <h4 class="text-lg font-semibold text-zinc-900 dark:text-white">Product Details</h4>
                                    <div class="space-y-3">
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Description</label>
                                            <p class="text-sm text-zinc-900 dark:text-white font-medium">{{ $selectedSupply->supply_description }}</p>
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Item Type</label>
                                            <p class="text-sm text-zinc-900 dark:text-white">{{ $selectedSupply->itemType->name ?? 'N/A' }}</p>
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Class</label>
                                            <p class="text-sm text-zinc-900 dark:text-white">
                                                @if($selectedSupply->itemClass && $selectedSupply->itemClass->name === 'consumable')
                                                    <span class="px-2 py-1 text-xs font-medium bg-green-100 text-green-800 rounded-full dark:bg-green-900 dark:text-green-300">
                                                        {{ $selectedSupply->itemClass->name }}
                                                    </span>
                                                @elseif($selectedSupply->itemClass && $selectedSupply->itemClass->name === 'accessories')
                                                    <span class="px-2 py-1 text-xs font-medium bg-blue-100 text-blue-800 rounded-full dark:bg-blue-900 dark:text-blue-300">
                                                        Accessories
                                                    </span>
                                                @else
                                                    <span class="px-2 py-1 text-xs font-medium bg-gray-100 text-gray-800 rounded-full dark:bg-gray-900 dark:text-gray-300">
                                                        {{ ucfirst($selectedSupply->itemClass ? $selectedSupply->itemClass->name : 'N/A') }}
                                                    </span>
                                                @endif
                                            </p>
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Allocation</label>
                                            <p class="text-sm text-zinc-900 dark:text-white">{{ $selectedSupply->allocation->name ?? 'N/A' }}</p>
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Current Stock</label>
                                            <p class="text-sm text-zinc-900 dark:text-white">{{ number_format($selectedSupply->supply_qty, 2) }} {{ $selectedSupply->supply_uom }}</p>
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Minimum Quantity</label>
                                            <p class="text-sm text-zinc-900 dark:text-white">{{ number_format($selectedSupply->supply_min_qty, 2) }} {{ $selectedSupply->supply_uom }}</p>
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Pricing</label>
                                            <div class="text-sm text-zinc-900 dark:text-white space-y-1">
                                                <p>Price 1: ₱{{ number_format($selectedSupply->supply_price1, 2) }}</p>
                                                <p>Price 2: ₱{{ number_format($selectedSupply->supply_price2, 2) }}</p>
                                                <p>Price 3: ₱{{ number_format($selectedSupply->supply_price3, 2) }}</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endif
                        </div>
                        <div class="flex items-center justify-end p-6 space-x-2 border-t border-gray-200 rounded-b dark:border-gray-600">
                            <x-button type="button" @click="show = false; $wire.closeQrModal()" variant="secondary">Close</x-button>
                            @if($selectedSupply)
                            <x-button type="button" onclick="window.open('/product/print/{{ $selectedSupply->supply_sku }}', '_blank', 'width=400,height=300')" variant="primary">
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

            <div class="py-4 px-3">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-4">
                        <label class="text-sm font-medium text-zinc-900 dark:text-white">Per Page:</label>
                        <x-dropdown wire:model.live="perPage" name="perPage" :options="[
                            '5' => '5',
                            '10' => '10',
                            '25' => '25',
                            '50' => '50',
                            '100' => '100',
                        ]" />
                    </div>
                    <div>
                        {{ $supplies->links() }}
                    </div>
                </div>
            </div>
        </x-collapsible-card>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Ensure filter buttons work properly
    const filterButtons = document.querySelectorAll('[data-filter="item-class"]');
    filterButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            console.log('Filter button clicked:', this.getAttribute('data-value'));
        });
    });

    // Ensure QR code buttons work properly
    const qrButtons = document.querySelectorAll('[data-action="qr-code"]');
    qrButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            console.log('QR button clicked for supply ID:', this.getAttribute('data-supply-id'));
        });
    });

    // Prevent any accidental modal triggers
    document.addEventListener('click', function(e) {
        // If clicking on a filter button, ensure no modals are triggered
        if (e.target.closest('[data-filter]')) {
            e.stopPropagation();
        }
    });
});
</script>
