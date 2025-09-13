<?php

namespace App\Enums;

enum RolesEnum: string
{
    

    case PURCHASER = 'Purchasing Head';
    case RAWMAT = 'Raw Material Personnel';
    case SUPPLY = 'Supply Personnel'; 
    case SUPERADMIN = 'Super Admin';
    case SALESMANAGER = 'Sales Manager';
    case WAREHOUSEMANAGER = 'Warehouse Manager';
    case FINANCEMANAGER = 'Finance Manager';
    case CUSTOMERSERVICE = 'Customer Service';
    case PRODUCTIONMANAGER = 'Production Manager';
    case SHIPPINGCOORDINATOR = 'Shipping Coordinator';
    case DEPARTMENTHEAD = 'Department Head';
    case AUTHOR = 'Author';
    case VIEWER = 'Viewer';
    case QUALITYCONTROL = 'Quality Control';
    case AUDITOR = 'Auditor';

    // extra helper to allow for greater customization of displayed values, without disclosing the name/value data directly
    public function label(): string
    {
        return match ($this) {
            static::VIEWER => 'Viewer',
            static::AUTHOR => 'Author',
            static::DEPARTMENTHEAD => 'Department Head',
            static::SHIPPINGCOORDINATOR =>'Shipping Coordinator',
            static::PRODUCTIONMANAGER => 'Production Manager',
            static::CUSTOMERSERVICE => 'Customer Service',
            static::FINANCEMANAGER => 'Finance Manager',
            static::WAREHOUSEMANAGER => 'Warehouse Manager',
            static::SALESMANAGER => 'Sales Manager',
            static::PURCHASER => 'Purchase Personnel',
            static::RAWMAT => 'Raw Material Personnel',
            static::SUPERADMIN => 'User Administrator',
            static::SUPPLY => 'Supply Personnel',
            static::QUALITYCONTROL => 'Quality Control',
            static::AUDITOR => 'Auditor',
        };
    }
}
