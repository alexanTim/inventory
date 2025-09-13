@php
    use App\Enums\Enum\PermissionEnum;
@endphp

<flux:sidebar sticky stashable class="bg-zinc-50 dark:bg-zinc-900 border-r rtl:border-r-0 rtl:border-l border-zinc-200 dark:border-zinc-700">
    <flux:sidebar.toggle class="lg:hidden" icon="x-mark" />

    <!-- Logo (restored) -->
    <div class="px-10 py-4 mt-2 border-b border-zinc-200 dark:border-zinc-700">
        <a href="{{ route('dashboard') }}" wire:navigate class="flex items-center text-xl whitespace-nowrap">
            <span style="font-family: Arial, sans-serif; font-weight: bold;" class="text-zinc-700 dark:text-white flex items-center">
                gentle w<img src="{{ asset('images/gentle_dark.png') }}" alt="logo" class="w-8 h-8 -mx-1 block dark:hidden"><img src="{{ asset('images/gentle_white.png') }}" alt="logo" class="w-8 h-8 -mx-1 hidden dark:block">lker
            </span>
        </a>
    </div>

    <flux:navlist variant="outline">
        <!-- Dashboard -->
        <flux:navlist.item icon="squares-2x2"
            href="{{ route('dashboard') }}"
            :current="request()->routeIs('dashboard')"
            wire:navigate>
            {{ __('Dashboard') }}
        </flux:navlist.item>

        @role([
            'Super Admin',
            'Admin',
            'Purchasing Head',
            'Raw Material Personnel',
            'Supply Personnel',
            'Production Manager',
            'Department Head',
            'Author',
            'Viewer'
        ])
        <!-- Request Management Group -->
        <flux:navlist.group expandable
            heading="{{ __('Request Management') }}" 
            class="hidden lg:grid cursor-pointer"
            :expanded="request()->routeIs('requisition.*') ? true : null">
            <flux:navlist.item icon="inbox-stack" href="{{ route('requisition.requestslip') }}"
                :current="request()->routeIs('requisition.requestslip*')"
                wire:navigate>
                {{ __('Request Slip') }}
            </flux:navlist.item>
        </flux:navlist.group>
        @endrole

        @role([
            'Super Admin',
            'Admin',
            'Purchasing Head',
            'Supply Personnel',
            'Sales Manager',
            'Warehouse Manager',
            'Production Manager',
            'Quality Control',
            'Shipping Coordinator',
            'Author',
            'Viewer'
        ])
        <!-- Product Management Group -->
        <flux:navlist.group expandable
            heading="{{ __('Product Management') }}" 
            class="hidden lg:grid cursor-pointer"
            :expanded="request()->routeIs('supplies.*') ? true : null">
            @role([
                'Super Admin', 'Admin', 'Purchasing Head', 'Supply Personnel',
                'Sales Manager', 'Warehouse Manager', 'Production Manager', 
                'Quality Control', 'Shipping Coordinator', 'Author', 'Viewer'
            ])
            <flux:navlist.item icon="archive-box" href="{{ route('supplies.inventory') }}" 
                :current="request()->routeIs('supplies.inventory*')"
                wire:navigate>
                {{ __('Inventory') }}
            </flux:navlist.item>
            @endrole

            @role([
                'Super Admin', 'Admin', 'Purchasing Head', 'Supply Personnel',
                'Finance Manager', 'Production Manager', 'Quality Control', 'Author', 'Viewer'
            ])
            <flux:navlist.item icon="document-text" href="{{ route('supplies.PurchaseOrder') }}" 
                :current="request()->routeIs('supplies.PurchaseOrder*')"
                wire:navigate>
                {{ __('Purchase Order') }}
            </flux:navlist.item>

             <flux:navlist.item icon="document-text" href="{{ route('supplies.DeliveryOrder') }}" 
                :current="request()->routeIs('supplies.DeliveryOrder*')"
                wire:navigate>
                {{ __('Delivery Order') }}
            </flux:navlist.item>
            @endrole
        </flux:navlist.group>
        @endrole

        @role([
            'Super Admin', 'Admin', 'Sales Manager', 'Finance Manager',
            'Customer Service', 'Quality Control', 'Shipping Coordinator', 'Author', 'Viewer'
        ])
        <!-- Sales Management Group -->
        <flux:navlist.group expandable
            heading="{{ __('Sales Management') }}" 
            class="hidden lg:grid cursor-pointer"
            :expanded="(request()->routeIs('salesorder.*') || request()->routeIs('salesreturn.*')) ? true : null">
            @role([
                'Super Admin', 'Admin', 'Sales Manager', 'Finance Manager',
                'Customer Service', 'Shipping Coordinator', 'Author', 'Viewer'
            ])
            <flux:navlist.item icon="shopping-cart" href="{{ route('salesorder.index') }}" 
                :current="request()->routeIs('salesorder.index*')"
                wire:navigate>
                {{ __('Sales Order') }}
            </flux:navlist.item>
            @endrole

            @role([
                'Super Admin', 'Admin', 'Sales Manager', 'Quality Control', 'Author', 'Viewer'
            ])
            <flux:navlist.item icon="arrow-uturn-left" href="{{ route('salesorder.return') }}" 
                :current="request()->routeIs('salesorder.return*')"
                wire:navigate>
                {{ __('Sales Return') }}
            </flux:navlist.item>
            @endrole
        </flux:navlist.group>
        @endrole

        @role([
            'Super Admin', 'Admin', 'Warehouse Manager', 'Customer Service', 
            'Shipping Coordinator', 'Author', 'Viewer'
        ])
        <!-- Shipment Management Group -->
        <flux:navlist.group expandable
            heading="{{ __('Shipment Management') }}" 
            class="hidden lg:grid cursor-pointer"
            :expanded="request()->routeIs('shipment.*') ? true : null">
            <flux:navlist.item icon="truck" href="{{ route('shipment.index') }}" 
                :current="request()->routeIs('shipment.index*')"
                wire:navigate>
                {{ __('Shipments') }}
            </flux:navlist.item>

            @can(\App\Enums\Enum\PermissionEnum::PROCESS_QRCODES_SHIPMENT->value)       
            <flux:navlist.item icon="qr-code" href="{{ route('shipment.qrscanner') }}" 
                :current="request()->routeIs('shipment.qrscanner*')"
                wire:navigate>
                {{ __('QR Scanner') }}
            </flux:navlist.item>
            @endcan
        </flux:navlist.group>
        @endrole

        @role([
            'Super Admin', 'Admin', 'Purchasing Head', 'Author', 'Viewer'
        ])
        <!-- Supplier Management Group -->
        <flux:navlist.group expandable
            heading="{{ __('Supplier Management') }}" 
            class="hidden lg:grid cursor-pointer"
            :expanded="request()->routeIs('supplier.*') ? true : null">
            <flux:navlist.item icon="building-office-2" href="{{ route('supplier.profile') }}" 
                :current="request()->routeIs('supplier.profile*')"
                wire:navigate>
                {{ __('Profile') }}
            </flux:navlist.item>
        </flux:navlist.group>
        @endrole

        @role([
            'Super Admin', 'Admin', 'Sales Manager', 'Customer Service', 'Author', 'Viewer'
        ])
        <!-- Customer Management Group -->
        <flux:navlist.group expandable
            heading="{{ __('Customer Management') }}" 
            class="hidden lg:grid cursor-pointer"
            :expanded="request()->routeIs('customer.*') ? true : null">
            <flux:navlist.item icon="user-group" href="{{ route('customer.profile') }}" 
                :current="request()->routeIs('customer.profile*')"
                wire:navigate>
                {{ __('Profile') }}
            </flux:navlist.item>
        </flux:navlist.group>
        @endrole

        @role([
            'Super Admin', 'Admin', 'Finance Manager', 'Author', 'Viewer'
        ])
        <!-- Finance Management Group -->
        <flux:navlist.group expandable
            heading="{{ __('Finance Management') }}" 
            class="hidden lg:grid cursor-pointer"
            :expanded="request()->routeIs('finance.*') ? true : null">
            <flux:navlist.item icon="banknotes" href="{{ route('finance.receivables') }}" 
                :current="request()->routeIs('finance.receivables*')"
                wire:navigate>
                {{ __('Receivables') }}
            </flux:navlist.item>
            <flux:navlist.item icon="credit-card" href="{{ route('finance.payables') }}" 
                :current="request()->routeIs('finance.payables*')"
                wire:navigate>
                {{ __('Payables') }}
            </flux:navlist.item>
            <flux:navlist.item icon="currency-dollar" href="{{ route('finance.expenses') }}" 
                :current="request()->routeIs('finance.expenses*')"
                wire:navigate>
                {{ __('Expenses') }}
            </flux:navlist.item>
            <!-- <flux:navlist.item icon="arrows-right-left" href="{{ route('finance.currency-conversion') }}" 
                :current="request()->routeIs('finance.currency-conversion*')"
                wire:navigate>
                {{ __('Currency Conversion') }}
            </flux:navlist.item> -->
        </flux:navlist.group>
        @endrole

        @role([
            'Super Admin', 'Admin', 'Department Head', 'Author', 'Viewer'
        ])
        <!-- Setup Section Group -->
        <flux:navlist.group expandable
            heading="{{ __('Setup') }}" 
            class="hidden lg:grid cursor-pointer"
            :expanded="(request()->routeIs('setup.*') || request()->routeIs('user.index') || request()->routeIs('roles.index')) ? true : null">
            <flux:navlist.item icon="building-office" href="{{ route('setup.department') }}" 
                :current="request()->routeIs('setup.department*')"
                wire:navigate>
                {{ __('Department') }}
            </flux:navlist.item>

            <flux:navlist.item icon="tag" href="{{ route('setup.itemType') }}" 
                :current="request()->routeIs('setup.itemType*')"
                wire:navigate>
                {{ __('Item Type') }}
            </flux:navlist.item>

            <flux:navlist.item icon="cube" href="{{ route('setup.itemClass') }}" 
                :current="request()->routeIs('setup.itemClass*')"
                wire:navigate>
                {{ __('Item Class') }}
            </flux:navlist.item>

            <flux:navlist.item icon="adjustments-horizontal" href="{{ route('setup.allocation') }}" 
                :current="request()->routeIs('setup.allocation*')"
                wire:navigate>
                {{ __('Allocation') }}
            </flux:navlist.item>

            @role(['Super Admin'])
            <flux:navlist.item icon="users" href="{{ route('user.index') }}"
                :current="request()->routeIs('user.index*')"
                wire:navigate>
                {{ __('Manage Users') }}
            </flux:navlist.item>
            @endrole

            @role(['Super Admin'])
            <flux:navlist.item icon="shield-check" href="{{ route('roles.index') }}"
                :current="request()->routeIs('roles.index*')"
                wire:navigate>
                {{ __('Roles & Permissions') }}
            </flux:navlist.item>
            @endrole
        </flux:navlist.group>
        @endrole

        @role([
            'Super Admin', 'Admin', 'Warehouse Manager'
        ])
        <!-- Warehouse Management Group -->
        <flux:navlist.group expandable
            heading="{{ __('Warehouse') }}" 
            class="hidden lg:grid cursor-pointer"
            :expanded="request()->routeIs('bodegero.*') ? true : null">
            <flux:navlist.item icon="arrow-down-tray" href="{{ route('bodegero.stockin') }}" 
                :current="request()->routeIs('bodegero.stockin*')"
                wire:navigate>
                {{ __('Stock In') }}
            </flux:navlist.item>

            <flux:navlist.item icon="arrow-up-tray" href="{{ route('bodegero.stockout') }}" 
                :current="request()->routeIs('bodegero.stockout*')"
                wire:navigate>
                {{ __('Stock Out') }}
            </flux:navlist.item>
        </flux:navlist.group>
        @endrole

    </flux:navlist>

    <!-- Spacer to push profile to bottom -->
        <flux:spacer />

    <!-- Secondary Navlist (Logs) -->
    <flux:navlist variant="outline">
        @role([
            'Admin', 'Purchasing Head', 'Supply Personnel', 'Sales Manager', 
            'Warehouse Manager', 'Finance Manager', 'Customer Service', 'Production Manager', 
            'Quality Control', 'Shipping Coordinator', 'Department Head', 'Auditor', 'Viewer'
        ])
        <flux:navlist.item icon="clipboard-document-list" href="{{ route('user.logs') }}" 
            :current="request()->routeIs('user.logs*')"
            wire:navigate>
            {{ __('User Logs') }}
        </flux:navlist.item>
        @endrole

        @role([
            'Super Admin', 'Admin', 'Viewer'
        ])
        <flux:navlist.item icon="clipboard-document-check" href="{{ route('activity.logs') }}" 
            :current="request()->routeIs('activity.logs*')"
            wire:navigate>
            {{ __('Activity Logs') }}
        </flux:navlist.item>
        @endrole
    </flux:navlist>

    <!-- Bottom Profile Section -->
    <flux:dropdown position="top" align="start" class="max-lg:hidden">
        <flux:profile :initials="auth()->user()->initials()" name="{{ auth()->user()->name }}" />
        <flux:menu>
                <flux:menu.radio.group>
                    <div class="p-0 text-sm font-normal">
                        <div class="flex items-center gap-2 px-1 py-1.5 text-start text-sm">
                            <span class="relative flex h-8 w-8 shrink-0 overflow-hidden rounded-lg">
                            <span class="flex h-full w-full items-center justify-center rounded-lg bg-neutral-200 text-black dark:bg-neutral-700 dark:text-white">
                                    {{ auth()->user()->initials() }}
                                </span>
                            </span>
                            <div class="grid flex-1 text-start text-sm leading-tight">
                                <span class="truncate font-semibold">{{ auth()->user()->name }}</span>
                                <span class="truncate text-xs">{{ auth()->user()->email }}</span>
                            </div>
                        </div>
                    </div>
                </flux:menu.radio.group>

                <flux:menu.separator />

            <flux:menu.item icon="cog-6-tooth" href="{{ route('settings.profile') }}" wire:navigate>
                {{ __('Settings') }}
            </flux:menu.item>

                <flux:menu.separator />



                <form method="POST" action="{{ route('logout') }}" class="w-full">
                    @csrf
                    <flux:menu.item as="button" type="submit" icon="arrow-right-start-on-rectangle" class="w-full">
                        {{ __('Log Out') }}
                    </flux:menu.item>
                </form>
            </flux:menu>
        </flux:dropdown>
    </flux:sidebar>

