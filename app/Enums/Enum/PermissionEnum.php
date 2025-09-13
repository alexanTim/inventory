<?php

namespace App\Enums\Enum;

enum PermissionEnum: string
{
    // Purchase-related
    case CREATE_SUPPLY_PURCHASE_ORDER = 'create supply purchase order';
    case APPROVE_SUPPLY_PURCHASE_ORDER = 'approve supply purchase order';
    case VIEW_SUPPLY_PURCHASE_ORDER = 'view supply purchase order';
    case DELETE_SUPPLY_PURCHASE_ORDER ='delete supply purchase order';
    case EDIT_SUPPLY_PURCHASE_ORDER = 'edit supply purchase order';

    case CREATE_RAWMAT_PURCHASE_ORDER = 'create rawmat purchase order';
    case APPROVE_RAWMAT_PURCHASE_ORDER = 'approve rawmat purchase order';
    case VIEW_RAWMAT_PURCHASE_ORDER = 'view rawmat purchase order';

    // Request Slip-related
    case VIEW_REQUEST_SLIP = 'view request slip';
    case APPROVE_REQUEST_SLIP = 'approve request slip';
    case CREATE_REQUEST_SLIP = 'create request slip';
    case DELETE_REQUEST_SLIP = 'delete request slip';
    case EDIT_REQUEST_SLIP = 'edit request slip';

    // Sales Order
    case CREATE_SALES_ORDER = 'create sales order';
    case APPROVE_SALES_ORDER = 'approve sales order';
    case VIEW_SALES_ORDER = 'view sales order';
    case EDIT_SALES_ORDER = 'edit sales order';
    case CHANGE_STATUS_SALES_ORDER = 'change status sales order'; 
    case DELETE_SALES_ORDER = 'delete sales order';   
    case CREATE_SALES_RETURN = 'create sales return';
    case EDIT_SALES_RETURN = 'edit sales return';
    case APPROVE_SALES_RETURN = 'approve sales return';
    case VIEW_SALES_RETURN = 'view sales return';

    // WAREHOUSE OPERATIONS 
    // Inventory Viewing   
    case VIEW_STOCK_BATCHES = 'view stock batches';

    // Stock Movement
    case RECEIVE_GOODS = 'receive goods';
    case PROCESS_STOCK_OUT = 'process stock out';

    case DISPATCH_GOODS = 'dispatch goods';
     

    // Stock Management
    case ADD_WAREHOUSE_ITEM = 'add warehouse item';
    case EDIT_WAREHOUSE_ITEM = 'edit warehouse item';
    case DELETE_WAREHOUSE_ITEM = 'delete warehouse item';
    

    /*
       Shipment Lifecycle
    */

    case CREATE_SHIPMENT = 'create shipment';
    case EDIT_SHIPMENT = 'edit shipment';
    case DELETE_SHIPMENT = 'delete shipment';
    case VIEW_SHIPMENT = 'view shipment';

    // Shipping Status Updates
    case MARK_AS_DISPATCHED = 'mark as dispatched';
    case MARK_AS_SHIPPED = 'mark as shipped';
    case MARK_AS_DELIVERED = 'mark as delivered';
    case CANCEL_SHIPMENT = 'cancel shipment';

    // Shipment Review & Approval
    case REVIEW_SHIPMENT = 'review shipment';
    case APPROVE_SHIPMENT = 'approve shipment';
    case REJECT_SHIPMENT = 'reject shipment';
    case PROCESS_QRCODES_SHIPMENT = 'process qrcode shipment';

    case TRACK_SHIPMENTS = 'track shipment';

    // Shipping Documents & Labels
    case VIEW_SHIPPING_DOCUMENTS = 'view shipping documents';
    case GENERATE_DELIVERY_RECEIPT = 'generate delivery receipt';
    case PRINT_SHIPPING_LABEL = 'print shipping label';

    // Tracking & Logistics
    case TRACK_SHIPMENT_STATUS = 'track shipment status';
    case ASSIGN_TRACKING_NUMBER = 'assign tracking number';

    // Carrier Management
    case MANAGE_CARRIERS = 'manage carriers';
    
    /**
     *  End Shipment Lifecycle
     */


    /* 
      INVENTORY OPERATIONS 
    */
    case VIEW_INVENTORY = 'view inventory';     
    case CREATE_INVENTORY = 'create inventory';
   
    case VIEW_INVENTORY_REPORTS = 'view inventory reports';

    // Inventory Add/Edit/Delete
    case ADD_INVENTORY_ITEM = 'add inventory item';
    case EDIT_INVENTORY_ITEM = 'edit inventory item';
    case DELETE_INVENTORY_ITEM = 'delete inventory item';

    // Stock Adjustments, INVENTORY
    case CREATE_STOCK_ADJUSTMENT = 'create stock adjustment'; 
    case APPROVE_STOCK_ADJUSTMENT = 'approve stock adjustment';

    // Stock Movements
    case STOCK_IN_ITEMS = 'stock in items';
    case STOCK_OUT_ITEMS = 'stock out items';
    case TRANSFER_STOCK = 'transfer stock';

    // Inventory Audits
    case INITIATE_INVENTORY_AUDIT = 'initiate inventory audit';
    case VIEW_AUDIT_RESULTS = 'view audit results';
    case COMPLETE_INVENTORY_AUDIT = 'complete inventory audit';
    case VIEW_AUDIT_LOGS = 'view audit logs';

    // Location/Category Management/ WAREHOUSE
    case MANAGE_WAREHOUSE_LOCATIONS = 'manage warehouse locations';
    case MANAGE_INVENTORY_CATEGORIES = 'manage inventory categories';

    /**
     *  FINANCE PERMISSIONS 
     */   
    case VIEW_FINANCIAL_REPORTS = 'view financial reports';
    case MANAGE_INVOICES = 'manage invoices';
    case APPROVE_PAYMENTS = 'approve payments';
    case VIEW_ACCOUNT_BALANCES = 'view account balances';
    case GENERATE_FINANCE_SUMMARIES = 'generate finance summaries';   
    case MANAGE_PAYROLL = 'manage payroll';
    case EXPORT_FINANCIAL_DATA = 'export financial data';
    case ACCESS_AUDIT_LOGS = 'access audit logs';

    // Financial Records
    case CREATE_RECEIVABLES = 'create receivables records';
    case EDIT_RECEIVABLES = 'edit receivables records';
    case DELETE_RECEIVABLES = 'delete receivables records';
    case VIEW_RECEIVABLES = 'view receivables records';

    case CREATE_PAYABLES = 'create payables records';
    case EDIT_PAYABLES = 'edit payables records';
    case DELETE_PAYABLES = 'delete payables records';
    case VIEW_PAYABLES = 'view payables records';

    case CREATE_EXPENSES = 'create expenses';
    case EDIT_EXPENSES = 'edit expenses';
    case DELETE_EXPENSES = 'delete expenses';
    case VIEW_EXPENSES = 'view expenses';

    /**
     *  END FINANCE PERMISSIONS
     */

    /**
     *  CUSTOMER MANAGEMENT
     */
    
    case VIEW_CUSTOMERS = 'view customers';
    case CREATE_CUSTOMERS = 'create customers';
    case EDIT_CUSTOMERS = 'edit customers';
    case DELETE_CUSTOMERS = 'delete customers';
    case EXPORT_CUSTOMERS = 'export customers';
    case VIEW_CUSTOMER_ORDERS = 'view customer orders';
    case VIEW_CUSTOMER_BALANCE = 'view customer balance';
    case MANAGE_CUSTOMER_CATEGORIES = 'manage customer categories';
    /**
     *  END CUSTOMER MANAGEMENT
     */

    /**
     *   SETUP 
     */

    case VIEW_DEPARTMENTS = 'view departments';
    case CREATE_DEPARTMENTS = 'create departments';
    case EDIT_DEPARTMENTS = 'edit departments';
    case DELETE_DEPARTMENTS = 'delete departments';

    case VIEW_USER = 'view user';
    case CREATE_USER = 'create user';
    case EDIT_USER = 'edit user';
    case DELETE_USER = 'delete user';
   
    case VIEW_ACTIVITY_LOGS = 'view activity logs';

    /**
     *  SUPPLIER
     */
    case VIEW_SUPPLIERS   = 'view suppliers';
    case CREATE_SUPPLIERS = 'create suppliers';
    case EDIT_SUPPLIERS   = 'edit suppliers';
    case DELETE_SUPPLIERS = 'delete suppliers';

    case VIEW_ITEM_TYPES  = 'view item types';
    case CREATE_ITEM_TYPES= 'create item types';
    case EDIT_ITEM_TYPES  = 'edit item types';
    case DELETE_ITEM_TYPES= 'delete item types';

    case VIEW_ITEM_CLASSES  = 'view item classes';
    case CREATE_ITEM_CLASSES= 'create item classes';
    case EDIT_ITEM_CLASSES  = 'edit item classes';
    case DELETE_ITEM_CLASSES= 'delete item classes';

    case VIEW_ALLOCATIONS = 'view allocations';
    case CREATE_ALLOCATIONS = 'create allocations';
    case EDIT_ALLOCATIONS = 'edit allocations';
    case DELETE_ALLOCATIONS = 'delete allocations';
    case APPROVE_ALLOCATIONS = 'approve allocations';
    

    public function label(): string
    {
        return match ($this) {

            static::VIEW_ALLOCATIONS => 'View allocation records',
            static::CREATE_ALLOCATIONS => 'Create new allocations',
            static::EDIT_ALLOCATIONS => 'Edit existing allocations',
            static::DELETE_ALLOCATIONS => 'Delete or revoke allocations',
            static::APPROVE_ALLOCATIONS => 'Approve or reject allocations',

            static::VIEW_ITEM_TYPES => 'View item types',
            static::CREATE_ITEM_TYPES => 'Create item types',
            static::EDIT_ITEM_TYPES => 'Edit item types',
            static::DELETE_ITEM_TYPES => 'Delete item types',

            static::VIEW_ITEM_CLASSES => 'View item classes',
            static::CREATE_ITEM_CLASSES => 'Create item classes',
            static::EDIT_ITEM_CLASSES => 'Edit item classes',
            static::DELETE_ITEM_CLASSES => 'Delete item classes',

            static::VIEW_SUPPLIERS => 'View suppliers',
            static::CREATE_SUPPLIERS =>  'Create suppliers',
            static::EDIT_SUPPLIERS => 'Edit suppliers',
            static::DELETE_SUPPLIERS => 'Delete suppliers',
            

            static::VIEW_ACTIVITY_LOGS => 'View activity logs',
            // CUSTOMER MANAGEMENT 
            static::VIEW_CUSTOMERS => 'View customers',
            static::CREATE_CUSTOMERS =>'Create customers',
            static::EDIT_CUSTOMERS => 'Edit customers',
            static::DELETE_CUSTOMERS => 'Delete customers',
            static::EXPORT_CUSTOMERS => 'Export customers',
            static::VIEW_CUSTOMER_ORDERS => 'View customer orders' ,
            static::VIEW_CUSTOMER_BALANCE => 'View customer balance',
            static::MANAGE_CUSTOMER_CATEGORIES => 'Manage customer categories',

            static::VIEW_DEPARTMENTS => 'View departments',
            static::CREATE_DEPARTMENTS => 'Create departments',
            static::EDIT_DEPARTMENTS => 'Edit departments',
            static::DELETE_DEPARTMENTS => 'Delete departments', 
                      
            static::VIEW_USER => 'View users',
            static::CREATE_USER => 'Create users',
            static::EDIT_USER => 'Edit users',
            static::DELETE_USER => 'Delete users', 

            // finance 
            static::VIEW_FINANCIAL_REPORTS => 'View financial reports',
            static::MANAGE_INVOICES => 'Manage invoices',
            static::APPROVE_PAYMENTS => 'Approve payments', 
            static::VIEW_ACCOUNT_BALANCES => 'View account balances', 
            static::GENERATE_FINANCE_SUMMARIES => 'Generate finance summaries',   
            static::MANAGE_PAYROLL => 'Manage payroll', 
            static::EXPORT_FINANCIAL_DATA => 'Export financial data', 
            static::ACCESS_AUDIT_LOGS => 'Access audit logs',

            // Financial Records 
            static::CREATE_RECEIVABLES => 'Create receivables records',
            static::EDIT_RECEIVABLES => 'Edit receivables records',
            static::DELETE_RECEIVABLES => 'Delete receivables records',
            static::VIEW_RECEIVABLES =>  'View receivables records',

            static::CREATE_PAYABLES => 'Create payables records',
            static::EDIT_PAYABLES => 'Edit payables records',
            static::DELETE_PAYABLES => 'Delete payables records',
            static::VIEW_PAYABLES => 'View payables records',

            static::CREATE_EXPENSES => 'Create expenses records',
            static::EDIT_EXPENSES => 'Edit expenses records',
            static::DELETE_EXPENSES => 'Delete expenses records',
            static::VIEW_EXPENSES => 'View expenses records',

            // SHIPPING OPERATIONS 
            static::CREATE_SHIPMENT => 'Create shipment',
            static::EDIT_SHIPMENT => 'Edit shipment',
            static::DELETE_SHIPMENT => 'Delete shipment',
            static::VIEW_SHIPMENT => 'View shipment',
            static::TRACK_SHIPMENTS => 'Track shipment',

            // Shipment Review & Approval
            static::APPROVE_SHIPMENT => 'Approve shipment',
            static::REJECT_SHIPMENT => 'Reject shipment',
            static::PROCESS_QRCODES_SHIPMENT => 'Process qrcode shipment',

            // Shipping Process            
            static::MARK_AS_SHIPPED => 'Mark as shipped',
            static::MARK_AS_DISPATCHED => 'Mark as dispatched',
            static::MARK_AS_DELIVERED => 'Mark as delivered',
            static::CANCEL_SHIPMENT => 'Cancel shipment',
            static::REVIEW_SHIPMENT => 'Review shipment',

            // Tracking & Documents           
            static::VIEW_SHIPPING_DOCUMENTS => 'View shipping documents',
            static::GENERATE_DELIVERY_RECEIPT => 'Generate delivery receipt',
            static::PRINT_SHIPPING_LABEL => 'Print shipping label',
            static::TRACK_SHIPMENT_STATUS => 'Track shipment status',

            // Carrier & Logistics Settings
            static::MANAGE_CARRIERS => 'Manage carriers',
            static::ASSIGN_TRACKING_NUMBER => 'Assign tracking number',
         

            // Inventory operations 
            static::VIEW_STOCK_BATCHES => 'View stock batches',

            // Stock Movement
            static::RECEIVE_GOODS => 'Receive goods',
            static::PROCESS_STOCK_OUT => 'Process stock out',
            
            static::DISPATCH_GOODS => 'Dispatch goods',
            static::TRANSFER_STOCK => 'Transfer stock',

            // Stock Management
            static::ADD_WAREHOUSE_ITEM => 'Add warehouse item',
            static::EDIT_WAREHOUSE_ITEM => 'Edit warehouse item',
            static::DELETE_WAREHOUSE_ITEM => 'Delete warehouse item',
            static::CREATE_STOCK_ADJUSTMENT => 'Create stock adjustment',
            static::APPROVE_STOCK_ADJUSTMENT => 'Approve stock adjustment',
          
            // stock movement 
            static::STOCK_OUT_ITEMS => 'Stock out items',
            static::STOCK_IN_ITEMS => 'Stock in items',

            // Warehouse Locations
            static::MANAGE_WAREHOUSE_LOCATIONS => 'Manage warehouse locations',
            static::MANAGE_INVENTORY_CATEGORIES => 'Manage inventory categories',

            static::VIEW_AUDIT_RESULTS => 'View audit results',
            static::COMPLETE_INVENTORY_AUDIT => 'Complete inventory audit',
            static::VIEW_AUDIT_LOGS => 'View audit logs',
 
            static::INITIATE_INVENTORY_AUDIT => 'Initiate inventory audit',
            static::VIEW_INVENTORY => 'View inventory',
           
            static::CREATE_INVENTORY => 'Create inventory',
            static::VIEW_INVENTORY_REPORTS => 'View inventory reports',

            static::ADD_INVENTORY_ITEM => 'Add inventory item',
            static::EDIT_INVENTORY_ITEM => 'Edit inventory reports',
            static::DELETE_INVENTORY_ITEM => 'Delete inventory reports',


            static::CREATE_SALES_ORDER => 'Create Sales Order',
            static::APPROVE_SALES_ORDER => 'Approve Sales Order',
            static::VIEW_SALES_ORDER => 'View Sales Order',
            static::EDIT_SALES_ORDER => 'Edit sales order',
            static::CHANGE_STATUS_SALES_ORDER => 'Change Status Sales Order',
            static::DELETE_SALES_ORDER => 'Delete Sales Order',
            static::CREATE_SALES_RETURN => 'Create Sales Return Order',
            static::EDIT_SALES_RETURN => 'Edit Sales Return Order',
            static::APPROVE_SALES_RETURN => 'Approve Sales Return Order',   
            static::VIEW_SALES_RETURN => 'View Sales Return Order',             

            static::CREATE_SUPPLY_PURCHASE_ORDER => 'Create Supply Purchase Order',
            static::APPROVE_SUPPLY_PURCHASE_ORDER => 'Approve Supply Purchase Order',
            static::VIEW_SUPPLY_PURCHASE_ORDER => 'View Supply Purchase Order',
            static::DELETE_SUPPLY_PURCHASE_ORDER => 'Delete Supply Purchase Order',
            static::EDIT_SUPPLY_PURCHASE_ORDER => 'Edit Supply Purchase Order',

            static::VIEW_REQUEST_SLIP => 'View Request Slip',
            static::APPROVE_REQUEST_SLIP => 'Approve Request Slip',
            static::CREATE_REQUEST_SLIP => 'Create Request Slip',
            static::DELETE_REQUEST_SLIP => 'Delete Request Slip',
            static::EDIT_REQUEST_SLIP => 'Edit Request Slip',

            static::CREATE_RAWMAT_PURCHASE_ORDER => 'Create Raw Material Purchase Order',
            static::APPROVE_RAWMAT_PURCHASE_ORDER => 'Approve Raw Material Purchase Order',
            static::VIEW_RAWMAT_PURCHASE_ORDER => 'View Raw Material Purchase Order',


          
        };
    }
}
