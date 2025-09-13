<x-slot:header>Dashboard</x-slot:header>
<x-slot:subheader>System Overview & Quick Actions</x-slot:subheader>

<div class="pt-4">
    <!-- Welcome Section -->
    <div class="mb-6 p-6 bg-gradient-to-r from-blue-50 to-indigo-50 dark:from-blue-900/20 dark:to-indigo-900/20 rounded-xl border border-blue-200 dark:border-blue-700">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-bold text-blue-900 dark:text-blue-100">
                    Welcome back, {{ auth()->user()->name }}! 👋
                </h2>
                <p class="text-blue-700 dark:text-blue-300 mt-1">
                    Here's what's happening in your system today
                </p>
            </div>
            <div class="text-right">
                <p class="text-sm text-blue-600 dark:text-blue-400">{{ now()->format('l, F j, Y') }}</p>
                <p class="text-xs text-blue-500 dark:text-blue-500">{{ now()->format('g:i A') }}</p>
            </div>
        </div>
    </div>

    <!-- Key Metrics Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Inventory Overview -->
        <div class="bg-white dark:bg-gray-800 p-6 rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Total Products</p>
                    <p class="text-3xl font-bold text-gray-900 dark:text-white">{{ number_format($totalProducts) }}</p>
                </div>
                <div class="p-3 bg-blue-100 dark:bg-blue-900/30 rounded-full">
                    <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                    </svg>
                </div>
            </div>
            <div class="mt-4 flex items-center text-sm">
                <span class="text-red-600 dark:text-red-400 font-medium">{{ $outOfStockProducts }}</span>
                <span class="text-gray-500 dark:text-gray-400 ml-1">out of stock</span>
                <span class="text-orange-600 dark:text-orange-400 font-medium ml-3">{{ $lowStockProducts }}</span>
                <span class="text-gray-500 dark:text-gray-400 ml-1">low stock</span>
            </div>
        </div>

        <!-- Purchase Orders -->
        <div class="bg-white dark:bg-gray-800 p-6 rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Purchase Orders</p>
                    <p class="text-3xl font-bold text-gray-900 dark:text-white">{{ number_format($totalPOs) }}</p>
                </div>
                <div class="p-3 bg-green-100 dark:bg-green-900/30 rounded-full">
                    <svg class="w-6 h-6 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                </div>
            </div>
            <div class="mt-4 flex items-center text-sm">
                <span class="text-yellow-600 dark:text-yellow-400 font-medium">{{ $pendingPOs }}</span>
                <span class="text-gray-500 dark:text-gray-400 ml-1">pending</span>
                <span class="text-green-600 dark:text-green-400 font-medium ml-3">{{ $approvedPOs }}</span>
                <span class="text-gray-500 dark:text-gray-400 ml-1">approved</span>
            </div>
        </div>

        <!-- Sales Orders -->
        <div class="bg-white dark:bg-gray-800 p-6 rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Sales Orders</p>
                    <p class="text-3xl font-bold text-gray-900 dark:text-white">{{ number_format($totalSalesOrders) }}</p>
                </div>
                <div class="p-3 bg-purple-100 dark:bg-purple-900/30 rounded-full">
                    <svg class="w-6 h-6 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                    </svg>
                </div>
            </div>
            <div class="mt-4 flex items-center text-sm">
                <span class="text-yellow-600 dark:text-yellow-400 font-medium">{{ $pendingSalesOrders }}</span>
                <span class="text-gray-500 dark:text-gray-400 ml-1">pending</span>
                <span class="text-green-600 dark:text-green-400 font-medium ml-3">{{ $approvedSalesOrders }}</span>
                <span class="text-gray-500 dark:text-gray-400 ml-1">approved</span>
            </div>
        </div>

        <!-- Financial Overview -->
        <div class="bg-white dark:bg-gray-800 p-6 rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Financial Status</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white">₱{{ number_format($totalReceivables) }}</p>
                </div>
                <div class="p-3 bg-emerald-100 dark:bg-emerald-900/30 rounded-full">
                    <svg class="w-6 h-6 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                    </svg>
                </div>
            </div>
            <div class="mt-4 text-sm">
                <div class="flex justify-between">
                    <span class="text-gray-500 dark:text-gray-400">Payables:</span>
                    <span class="text-red-600 dark:text-red-400 font-medium">₱{{ number_format($totalPayables) }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-500 dark:text-gray-400">Expenses:</span>
                    <span class="text-orange-600 dark:text-orange-400 font-medium">₱{{ number_format($totalExpenses) }}</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Content Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Recent Activities -->
        <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm">
            <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Recent Activities</h3>
                <p class="text-sm text-gray-600 dark:text-gray-400">Latest system activities and updates</p>
            </div>
            <div class="p-6">
                <!-- Recent Purchase Orders -->
                @if($recentPurchaseOrders->count() > 0)
                <div class="mb-6">
                    <h4 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-3 flex items-center">
                        <svg class="w-4 h-4 mr-2 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        Recent Purchase Orders
                    </h4>
                    <div class="space-y-2">
                        @foreach($recentPurchaseOrders as $po)
                        <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                            <div>
                                <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $po->po_num }}</p>
                                <p class="text-xs text-gray-500 dark:text-gray-400">{{ $po->supplier->name ?? 'N/A' }}</p>
                            </div>
                            <span class="px-2 py-1 text-xs rounded-full {{ $po->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : 'bg-green-100 text-green-800' }}">
                                {{ ucfirst($po->status) }}
                            </span>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif

                <!-- Recent Sales Orders -->
                @if($recentSalesOrders->count() > 0)
                <div class="mb-6">
                    <h4 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-3 flex items-center">
                        <svg class="w-4 h-4 mr-2 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                        </svg>
                        Recent Sales Orders
                    </h4>
                    <div class="space-y-2">
                        @foreach($recentSalesOrders as $so)
                        <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                            <div>
                                <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $so->sales_order_number }}</p>
                                <p class="text-xs text-gray-500 dark:text-gray-400">{{ $so->customer->name ?? 'N/A' }}</p>
                            </div>
                            <span class="px-2 py-1 text-xs rounded-full {{ $so->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : 'bg-green-100 text-green-800' }}">
                                {{ ucfirst($so->status) }}
                            </span>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif

                <!-- Recent Request Slips -->
                @if($recentRequestSlips->count() > 0)
                <div class="mb-6">
                    <h4 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-3 flex items-center">
                        <svg class="w-4 h-4 mr-2 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                        </svg>
                        Recent Request Slips
                    </h4>
                    <div class="space-y-2">
                        @foreach($recentRequestSlips as $rs)
                        <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                            <div>
                                <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $rs->purpose }}</p>
                                <p class="text-xs text-gray-500 dark:text-gray-400">{{ $rs->sentFrom->name ?? 'N/A' }} → {{ $rs->requestedBy->name ?? 'N/A' }}</p>
                            </div>
                            <span class="px-2 py-1 text-xs rounded-full {{ $rs->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : ($rs->status === 'approved' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800') }}">
                                {{ ucfirst($rs->status) }}
                            </span>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif
            </div>
        </div>

        <!-- Alerts & Notifications -->
        <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm">
            <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Alerts & Notifications</h3>
                <p class="text-sm text-gray-600 dark:text-gray-400">Important system alerts and warnings</p>
            </div>
            <div class="p-6">
                <!-- Low Stock Alerts -->
                @if($lowStockAlerts->count() > 0)
                <div class="mb-6">
                    <h4 class="text-sm font-medium text-red-700 dark:text-red-300 mb-3 flex items-center">
                        <svg class="w-4 h-4 mr-2 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                        </svg>
                        Low Stock Alerts
                    </h4>
                    <div class="space-y-2">
                        @foreach($lowStockAlerts as $product)
                        <div class="flex items-center justify-between p-3 bg-red-50 dark:bg-red-900/20 rounded-lg border border-red-200 dark:border-red-800">
                            <div>
                                <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $product->supply_description }}</p>
                                <p class="text-xs text-gray-500 dark:text-gray-400">{{ $product->itemClass->name ?? 'N/A' }} • {{ $product->itemType->name ?? 'N/A' }}</p>
                            </div>
                            <span class="px-2 py-1 text-xs rounded-full {{ $product->supply_qty <= 0 ? 'bg-red-100 text-red-800' : 'bg-orange-100 text-orange-800' }}">
                                {{ $product->supply_qty <= 0 ? 'Out of Stock' : 'Low Stock: ' . $product->supply_qty }}
                            </span>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif

                <!-- Quick Actions -->
                <div>
                    <h4 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">Quick Actions</h4>
                    <div class="grid grid-cols-2 gap-3">
                        <a href="{{ route('supplies.inventory') }}" class="p-3 bg-blue-50 dark:bg-blue-900/20 rounded-lg border border-blue-200 dark:border-blue-800 hover:bg-blue-100 dark:hover:bg-blue-900/30 transition-colors">
                            <div class="text-center">
                                <svg class="w-6 h-6 text-blue-600 dark:text-blue-400 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                                </svg>
                                <p class="text-sm font-medium text-blue-900 dark:text-blue-100">Inventory</p>
                            </div>
                        </a>
                        <a href="{{ route('supplies.PurchaseOrder') }}" class="p-3 bg-green-50 dark:bg-green-900/20 rounded-lg border border-green-200 dark:border-green-800 hover:bg-green-100 dark:hover:bg-green-900/30 transition-colors">
                            <div class="text-center">
                                <svg class="w-6 h-6 text-green-600 dark:text-green-400 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                                <p class="text-sm font-medium text-green-900 dark:text-green-100">Purchase Orders</p>
                            </div>
                        </a>
                        <a href="{{ route('salesorder.index') }}" class="p-3 bg-purple-50 dark:bg-purple-900/20 rounded-lg border border-purple-200 dark:border-purple-800 hover:bg-purple-100 dark:hover:bg-purple-900/30 transition-colors">
                            <div class="text-center">
                                <svg class="w-6 h-6 text-purple-600 dark:text-purple-400 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                                </svg>
                                <p class="text-sm font-medium text-purple-900 dark:text-purple-100">Sales Orders</p>
                            </div>
                        </a>
                        <a href="{{ route('requisition.requestslip') }}" class="p-3 bg-orange-50 dark:bg-orange-900/20 rounded-lg border border-orange-200 dark:border-orange-800 hover:bg-orange-100 dark:hover:bg-orange-900/30 transition-colors">
                            <div class="text-center">
                                <svg class="w-6 h-6 text-orange-600 dark:text-orange-400 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                                </svg>
                                <p class="text-sm font-medium text-orange-900 dark:text-orange-100">Request Slips</p>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- System Status Footer -->
    <div class="mt-8 p-4 bg-gray-50 dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700">
        <div class="flex items-center justify-between text-sm text-gray-600 dark:text-gray-400">
            <div class="flex items-center space-x-4">
                <span class="flex items-center">
                    <div class="w-2 h-2 bg-green-500 rounded-full mr-2"></div>
                    System Online
                </span>
                <span class="flex items-center">
                    <div class="w-2 h-2 bg-blue-500 rounded-full mr-2"></div>
                    {{ $totalProducts }} Products
                </span>
                <span class="flex items-center">
                    <div class="w-2 h-2 bg-purple-500 rounded-full mr-2"></div>
                    {{ $totalPOs + $totalSalesOrders }} Total Orders
                </span>
            </div>
            <div>
                Last updated: {{ now()->format('g:i A') }}
            </div>
        </div>
    </div>
</div>
