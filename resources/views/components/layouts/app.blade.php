<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    @include('partials.head')
    <link rel="icon" type="image/png" href="{{ asset('images/gentle_dark.png') }}" class="block dark:hidden">
    <link rel="icon" type="image/png" href="{{ asset('images/gentle_white.png') }}" class="dark:block hidden">

</head>

<body class="min-h-screen bg-white dark:bg-zinc-900">
    <!-- Include the sidebar component -->
    <x-layouts.app.sidebar />

   <!-- Main Content -->
    <flux:main>
        <!-- Main Content Header -->
        <flux:header class="border-b border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-900 mb-4 mt-0.45">
            <div class="flex items-center ">
                <h1 class="text-2xl font-bold text-zinc-900 dark:text-white">
                    @php
                        $pageTitle = 'Dashboard';
                        $routeName = request()->route() ? request()->route()->getName() : 'dashboard';
                        
                        $routeTitles = [
                            'dashboard' => 'Dashboard',
                            'requisition.requestslip' => 'Request Slip',
                            'requisition.requestslip.create' => 'Create Request Slip',
                            'requisition.requestslip.view' => 'Request Slip Detail',
                            'supplies.inventory' => 'Inventory',
                            'supplies.inventory.stocks' => 'Stock Batches',
                            'supplies.PurchaseOrder' => 'Purchase Order',
                            'supplies.DeliveryOrder.create' => 'Create Delivery Order',
                            'supplies.DeliveryOrder.view' => 'Delivery Order',
                            'supplies.DeliveryOrder' => 'Delivery Order',
                            'supplies.PurchaseOrder.create' => 'Create Purchase Order',
                            'supplies.PurchaseOrder.show' => 'Purchase Order Details',
                            'supplies.PurchaseOrder.showForApproval' => 'Purchase Order Approval',
                            'supplies.PurchaseOrder.showReceivingReport' => 'Receiving Report',
                            'supplies.PurchaseOrder.showStandard' => 'Purchase Order Standard',
                            'supplies.PurchaseOrder.edit' => 'Edit Purchase Order',
                            'supplies.print' => 'Print Supply QR Code',
                            'purchase-orders.print' => 'Print Purchase Order',
                            'salesorder.index' => 'Sales Order',
                            'salesorder.view' => 'Sales Order Details',
                            'salesorder.return' => 'Sales Return',
                            'salesreturn.view' => 'Sales Return Details',
                            'shipment.index' => 'Shipments',
                            'shipping.view' => 'Shipment Details',
                            'shipment.qrscanner' => 'QR Scanner',
                            'supplier.profile' => 'Supplier Profile',
                            'customer.profile' => 'Customer Profile',
                            'setup.department' => 'Department',
                            'setup.itemType' => 'Item Type',
                            'setup.allocation' => 'Allocation',
                            'user.index' => 'Manage Users',
                            'roles.index' => 'Roles & Permissions',
                            'bodegero.stockin' => 'Stock In',
                            'bodegero.stockout' => 'Stock Out',
                            'user.logs' => 'User Logs',
                            'activity.logs' => 'Activity Logs',
                            'settings.profile' => 'Settings',
                            'settings.password' => 'Password Settings',
                            'settings.appearance' => 'Appearance Settings',
                            'finance.receivables' => 'Receivables',
                            'finance.payables' => 'Payables',
                            'finance.expenses' => 'Expenses',
                            'finance.currency-conversion' => 'Currency Conversion',
                            'prw.inventory' => 'PRW Inventory',
                            'prw.purchaseorder' => 'PRW Purchase Order',
                            'prw.purchaseorder.create' => 'Create PRW Purchase Order',
                            'prw.purchaseorder.edit' => 'Edit PRW Purchase Order',
                            'prw.purchaseorder.show' => 'PRW Purchase Order Details',
                            'prw.purchaseorder.viewItem' => 'PRW Purchase Order Items',
                            'prw.profile' => 'PRW Profile',
                            'camera.test' => 'Camera Test',
                            'qr.test' => 'QR Test'
                        ];
                        
                        $pageTitle = $routeTitles[$routeName] ?? 'Dashboard';
                    @endphp
                    {{ $pageTitle }}
                </h1>
            </div>
        </flux:header>
        
        <!-- Page Content -->
        <div class="px-6">
            {{ $slot }}
        </div>
    </flux:main>

    @fluxScripts
    @livewireScripts
</body>

</html>