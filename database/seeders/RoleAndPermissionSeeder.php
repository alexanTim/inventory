<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;
use App\Models\User;
use App\Models\Department;
use App\Enums\Enum\PermissionEnum;
use App\Enums\RolesEnum;

class RoleAndPermissionSeeder extends Seeder
{
    public function run(): void
    {
        // ✅ Step 1: Create all permissions
        foreach (PermissionEnum::cases() as $permission) {
            Permission::updateOrCreate(
                ['name' => $permission->value],
                ['guard_name' => 'web']
            );
        }

        // ✅ Step 2: Define roles and their permissions
        $roles = [
            RolesEnum::PURCHASER->value => [
                // Purchase Management
                PermissionEnum::CREATE_SUPPLY_PURCHASE_ORDER,
                PermissionEnum::APPROVE_SUPPLY_PURCHASE_ORDER,
                PermissionEnum::VIEW_SUPPLY_PURCHASE_ORDER,
                PermissionEnum::EDIT_SUPPLY_PURCHASE_ORDER,
                PermissionEnum::DELETE_SUPPLY_PURCHASE_ORDER,

                PermissionEnum::CREATE_RAWMAT_PURCHASE_ORDER,
                PermissionEnum::APPROVE_RAWMAT_PURCHASE_ORDER,
                PermissionEnum::VIEW_RAWMAT_PURCHASE_ORDER,

                // Requisition System
                PermissionEnum::CREATE_REQUEST_SLIP,
                PermissionEnum::APPROVE_REQUEST_SLIP,
                PermissionEnum::VIEW_REQUEST_SLIP,
                PermissionEnum::DELETE_REQUEST_SLIP,
                PermissionEnum::EDIT_REQUEST_SLIP,

                // Supplier Management
                PermissionEnum::VIEW_SUPPLIERS,
                PermissionEnum::CREATE_SUPPLIERS,
                PermissionEnum::EDIT_SUPPLIERS,
                PermissionEnum::DELETE_SUPPLIERS,

                // Inventory (View)
                PermissionEnum::VIEW_INVENTORY,
            ],

            RolesEnum::SUPPLY->value => [
                // Supply Inventory
                PermissionEnum::VIEW_INVENTORY,
                PermissionEnum::CREATE_INVENTORY,
                PermissionEnum::ADD_INVENTORY_ITEM,
                PermissionEnum::EDIT_INVENTORY_ITEM,
                PermissionEnum::DELETE_INVENTORY_ITEM,

                // Requisition System (Limited)
                PermissionEnum::CREATE_REQUEST_SLIP,
                PermissionEnum::VIEW_REQUEST_SLIP,

                // Purchase Orders (Supplies)
                PermissionEnum::VIEW_SUPPLY_PURCHASE_ORDER,
            ],

            RolesEnum::SALESMANAGER->value => [
                // Sales Management
                PermissionEnum::CREATE_SALES_ORDER,
                PermissionEnum::APPROVE_SALES_ORDER,
                PermissionEnum::VIEW_SALES_ORDER,
                PermissionEnum::EDIT_SALES_ORDER,
                PermissionEnum::CHANGE_STATUS_SALES_ORDER,
                PermissionEnum::DELETE_SALES_ORDER,
                PermissionEnum::CREATE_SALES_RETURN,
                PermissionEnum::EDIT_SALES_RETURN,
                PermissionEnum::APPROVE_SALES_RETURN,
                PermissionEnum::VIEW_SALES_RETURN,

                // Customer Management
                PermissionEnum::VIEW_CUSTOMERS,
                PermissionEnum::CREATE_CUSTOMERS,
                PermissionEnum::EDIT_CUSTOMERS,
                PermissionEnum::DELETE_CUSTOMERS,
                PermissionEnum::EXPORT_CUSTOMERS,
                PermissionEnum::VIEW_CUSTOMER_ORDERS,
                PermissionEnum::VIEW_CUSTOMER_BALANCE,
                PermissionEnum::MANAGE_CUSTOMER_CATEGORIES,

                // Inventory (View)
                PermissionEnum::VIEW_INVENTORY,
            ],

            RolesEnum::WAREHOUSEMANAGER->value => [
                // Warehouse Operations
                PermissionEnum::VIEW_INVENTORY,
                PermissionEnum::VIEW_STOCK_BATCHES,
                PermissionEnum::RECEIVE_GOODS,
                PermissionEnum::DISPATCH_GOODS,
                PermissionEnum::TRANSFER_STOCK,
                PermissionEnum::CREATE_STOCK_ADJUSTMENT,
                PermissionEnum::APPROVE_STOCK_ADJUSTMENT,
                PermissionEnum::INITIATE_INVENTORY_AUDIT,
                PermissionEnum::VIEW_AUDIT_RESULTS,
                PermissionEnum::ADD_WAREHOUSE_ITEM,
                PermissionEnum::EDIT_WAREHOUSE_ITEM,
                PermissionEnum::DELETE_WAREHOUSE_ITEM,
                PermissionEnum::MANAGE_WAREHOUSE_LOCATIONS,

                // Inventory Management
                PermissionEnum::STOCK_IN_ITEMS,
                PermissionEnum::STOCK_OUT_ITEMS,
                PermissionEnum::VIEW_INVENTORY_REPORTS,
                PermissionEnum::MANAGE_INVENTORY_CATEGORIES,

                // Shipping & Logistics
                PermissionEnum::CREATE_SHIPMENT,
                PermissionEnum::EDIT_SHIPMENT,
                PermissionEnum::DELETE_SHIPMENT,
                PermissionEnum::VIEW_SHIPMENT,
                PermissionEnum::MARK_AS_DISPATCHED,
                PermissionEnum::MARK_AS_SHIPPED,
                PermissionEnum::MARK_AS_DELIVERED,
                PermissionEnum::CANCEL_SHIPMENT,
                PermissionEnum::REVIEW_SHIPMENT,
                PermissionEnum::APPROVE_SHIPMENT,
                PermissionEnum::REJECT_SHIPMENT,
                PermissionEnum::VIEW_SHIPPING_DOCUMENTS,
                PermissionEnum::GENERATE_DELIVERY_RECEIPT,
                PermissionEnum::PRINT_SHIPPING_LABEL,
                PermissionEnum::TRACK_SHIPMENT_STATUS,
                PermissionEnum::ASSIGN_TRACKING_NUMBER,
                PermissionEnum::MANAGE_CARRIERS,
                PermissionEnum::PROCESS_QRCODES_SHIPMENT,
            ],

            RolesEnum::FINANCEMANAGER->value => [
                // Finance Management
                PermissionEnum::VIEW_FINANCIAL_REPORTS,
                PermissionEnum::MANAGE_INVOICES,
                PermissionEnum::APPROVE_PAYMENTS,
                PermissionEnum::VIEW_ACCOUNT_BALANCES,
                PermissionEnum::GENERATE_FINANCE_SUMMARIES,
                PermissionEnum::MANAGE_PAYROLL,
                PermissionEnum::EXPORT_FINANCIAL_DATA,
                PermissionEnum::ACCESS_AUDIT_LOGS,

                // Receivables/Payables
                PermissionEnum::CREATE_RECEIVABLES,
                PermissionEnum::EDIT_RECEIVABLES,
                PermissionEnum::DELETE_RECEIVABLES,
                PermissionEnum::VIEW_RECEIVABLES,
                PermissionEnum::CREATE_PAYABLES,
                PermissionEnum::EDIT_PAYABLES,
                PermissionEnum::DELETE_PAYABLES,
                PermissionEnum::VIEW_PAYABLES,

                // Expenses
                PermissionEnum::CREATE_EXPENSES,
                PermissionEnum::EDIT_EXPENSES,
                PermissionEnum::DELETE_EXPENSES,
                PermissionEnum::VIEW_EXPENSES,

                // Sales (View)
                PermissionEnum::VIEW_SALES_ORDER,
                PermissionEnum::VIEW_SALES_RETURN,

                // Purchase Orders (View)
                PermissionEnum::VIEW_SUPPLY_PURCHASE_ORDER,
                PermissionEnum::VIEW_RAWMAT_PURCHASE_ORDER,
            ],

            RolesEnum::CUSTOMERSERVICE->value => [
                // Sales Management (View)
                PermissionEnum::VIEW_SALES_ORDER,
                PermissionEnum::VIEW_SALES_RETURN,

                // Customer Management
                PermissionEnum::VIEW_CUSTOMERS,
                PermissionEnum::CREATE_CUSTOMERS,
                PermissionEnum::EDIT_CUSTOMERS,
                PermissionEnum::DELETE_CUSTOMERS,
                PermissionEnum::EXPORT_CUSTOMERS,
                PermissionEnum::VIEW_CUSTOMER_ORDERS,
                PermissionEnum::VIEW_CUSTOMER_BALANCE,
                PermissionEnum::MANAGE_CUSTOMER_CATEGORIES,

                // Shipping (View)
                PermissionEnum::VIEW_SHIPMENT,
                PermissionEnum::TRACK_SHIPMENTS,
            ],

            RolesEnum::PRODUCTIONMANAGER->value => [
                // Inventory (View)
                PermissionEnum::VIEW_INVENTORY,
                PermissionEnum::VIEW_INVENTORY_REPORTS,

                // Requisition System
                PermissionEnum::CREATE_REQUEST_SLIP,
                PermissionEnum::VIEW_REQUEST_SLIP,

                // Purchase Orders (View)
                PermissionEnum::VIEW_SUPPLY_PURCHASE_ORDER,
                PermissionEnum::VIEW_RAWMAT_PURCHASE_ORDER,
            ],

            RolesEnum::QUALITYCONTROL->value => [
                // Inventory (View)
                PermissionEnum::VIEW_INVENTORY,

                // Sales Returns
                PermissionEnum::VIEW_SALES_RETURN,
                PermissionEnum::CREATE_SALES_RETURN,
                PermissionEnum::EDIT_SALES_RETURN,
                PermissionEnum::APPROVE_SALES_RETURN,

                // Purchase Orders (View)
                PermissionEnum::VIEW_SUPPLY_PURCHASE_ORDER,
                PermissionEnum::VIEW_RAWMAT_PURCHASE_ORDER,
            ],

            RolesEnum::SHIPPINGCOORDINATOR->value => [
                // Shipping & Logistics
                PermissionEnum::CREATE_SHIPMENT,
                PermissionEnum::EDIT_SHIPMENT,
                PermissionEnum::DELETE_SHIPMENT,
                PermissionEnum::VIEW_SHIPMENT,
                PermissionEnum::MARK_AS_DISPATCHED,
                PermissionEnum::MARK_AS_SHIPPED,
                PermissionEnum::MARK_AS_DELIVERED,
                PermissionEnum::CANCEL_SHIPMENT,
                PermissionEnum::REVIEW_SHIPMENT,
                PermissionEnum::APPROVE_SHIPMENT,
                PermissionEnum::REJECT_SHIPMENT,
                PermissionEnum::VIEW_SHIPPING_DOCUMENTS,
                PermissionEnum::GENERATE_DELIVERY_RECEIPT,
                PermissionEnum::PRINT_SHIPPING_LABEL,
                PermissionEnum::TRACK_SHIPMENT_STATUS,
                PermissionEnum::ASSIGN_TRACKING_NUMBER,
                PermissionEnum::MANAGE_CARRIERS,
                PermissionEnum::PROCESS_QRCODES_SHIPMENT,

                // Sales (View)
                PermissionEnum::VIEW_SALES_ORDER,

                // Inventory (View)
                PermissionEnum::VIEW_INVENTORY,
            ],

            RolesEnum::DEPARTMENTHEAD->value => [
                // Requisition System
                PermissionEnum::CREATE_REQUEST_SLIP,
                PermissionEnum::EDIT_REQUEST_SLIP,
                PermissionEnum::VIEW_REQUEST_SLIP,
                PermissionEnum::APPROVE_REQUEST_SLIP,

                // Setup (Department)
                PermissionEnum::VIEW_DEPARTMENTS,
                PermissionEnum::CREATE_DEPARTMENTS,
                PermissionEnum::EDIT_DEPARTMENTS,
                PermissionEnum::DELETE_DEPARTMENTS,

                // Setup (Item Types)
                PermissionEnum::VIEW_ITEM_TYPES,
                PermissionEnum::CREATE_ITEM_TYPES,
                PermissionEnum::EDIT_ITEM_TYPES,
                PermissionEnum::DELETE_ITEM_TYPES,

                // Setup (Item Classes)
                PermissionEnum::VIEW_ITEM_CLASSES,
                PermissionEnum::CREATE_ITEM_CLASSES,
                PermissionEnum::EDIT_ITEM_CLASSES,
                PermissionEnum::DELETE_ITEM_CLASSES,

                // Setup (Allocations)
                PermissionEnum::VIEW_ALLOCATIONS,
                PermissionEnum::CREATE_ALLOCATIONS,
                PermissionEnum::EDIT_ALLOCATIONS,
                PermissionEnum::DELETE_ALLOCATIONS,

                // User Management (Department)
                PermissionEnum::VIEW_USER,
                PermissionEnum::CREATE_USER,
                PermissionEnum::EDIT_USER,
                PermissionEnum::DELETE_USER,
            ],

            RolesEnum::AUDITOR->value => [
                // Activity Logs
                PermissionEnum::VIEW_ACTIVITY_LOGS,

                // All Modules (View Only)
                PermissionEnum::VIEW_REQUEST_SLIP,
                PermissionEnum::VIEW_INVENTORY,
                PermissionEnum::VIEW_SHIPMENT,
                PermissionEnum::VIEW_SALES_ORDER,
                PermissionEnum::VIEW_SALES_RETURN,
                PermissionEnum::VIEW_CUSTOMERS,
                PermissionEnum::VIEW_SUPPLY_PURCHASE_ORDER,
                PermissionEnum::VIEW_RAWMAT_PURCHASE_ORDER,
                PermissionEnum::VIEW_PAYABLES,
                PermissionEnum::VIEW_RECEIVABLES,
                PermissionEnum::VIEW_EXPENSES,
                PermissionEnum::VIEW_SUPPLIERS,
                PermissionEnum::VIEW_DEPARTMENTS,
                PermissionEnum::VIEW_ITEM_TYPES,
                PermissionEnum::VIEW_ITEM_CLASSES,
                PermissionEnum::VIEW_ALLOCATIONS,
            ],

            RolesEnum::VIEWER->value => [
                // All Modules (View Only)
                PermissionEnum::VIEW_REQUEST_SLIP,
                PermissionEnum::VIEW_INVENTORY,
                PermissionEnum::VIEW_SHIPMENT,
                PermissionEnum::VIEW_SALES_ORDER,
                PermissionEnum::VIEW_SALES_RETURN,
                PermissionEnum::VIEW_CUSTOMERS,
                PermissionEnum::VIEW_SUPPLY_PURCHASE_ORDER,
                PermissionEnum::VIEW_RAWMAT_PURCHASE_ORDER,
                PermissionEnum::VIEW_PAYABLES,
                PermissionEnum::VIEW_RECEIVABLES,
                PermissionEnum::VIEW_EXPENSES,
                PermissionEnum::VIEW_SUPPLIERS,
                PermissionEnum::VIEW_DEPARTMENTS,
                PermissionEnum::VIEW_ITEM_TYPES,
                PermissionEnum::VIEW_ITEM_CLASSES,
                PermissionEnum::VIEW_ALLOCATIONS,
            ],

            RolesEnum::SUPERADMIN->value => PermissionEnum::cases(), // all permissions
        ];

        // ✅ Step 3: Create roles and assign permissions
        foreach ($roles as $roleName => $permissions) {
            $role = Role::updateOrCreate(
                ['name' => $roleName, 'guard_name' => 'web']
            );

            $permissionNames = collect($permissions)->map(fn($p) => $p->value)->toArray();
            $role->syncPermissions($permissionNames);
        }

        // Create comprehensive users using the new seeder
        $this->call(ComprehensiveUserSeeder::class);
    }
}
