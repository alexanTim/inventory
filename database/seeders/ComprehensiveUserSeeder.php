<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Department;
use App\Enums\RolesEnum;
use App\Enums\Enum\PermissionEnum;

class ComprehensiveUserSeeder extends Seeder
{
    public function run(): void
    {
        // Get or create departments
        $adminDept = Department::firstOrCreate(['name' => 'Admin Department']);
        $purchaseDept = Department::firstOrCreate(['name' => 'Purchase Department']);
        $supplyDept = Department::firstOrCreate(['name' => 'Supply Department']);
        $salesDept = Department::firstOrCreate(['name' => 'Sales Department']);
        $warehouseDept = Department::firstOrCreate(['name' => 'Warehouse Department']);
        $financeDept = Department::firstOrCreate(['name' => 'Finance Department']);
        $customerServiceDept = Department::firstOrCreate(['name' => 'Customer Service Department']);
        $productionDept = Department::firstOrCreate(['name' => 'Production Department']);
        $qualityDept = Department::firstOrCreate(['name' => 'Quality Control Department']);
        $shippingDept = Department::firstOrCreate(['name' => 'Shipping Department']);
        $auditDept = Department::firstOrCreate(['name' => 'Audit Department']);

        // Create users with their respective roles
        $users = [
            [
                'name' => 'Super Admin',
                'email' => 'superadmin@gentlewalker.com',
                'password' => 'admin123!',
                'department_id' => $adminDept->id,
                'role' => RolesEnum::SUPERADMIN->value,
                'description' => 'Full system administrator'
            ],
            [
                'name' => 'Purchasing Head',
                'email' => 'purchasing@gentlewalker.com',
                'password' => 'admin123!',
                'department_id' => $purchaseDept->id,
                'role' => RolesEnum::PURCHASER->value,
                'description' => 'Manages all purchasing activities'
            ],
            [
                'name' => 'Supply Personnel',
                'email' => 'supply@gentlewalker.com',
                'password' => 'admin123!',
                'department_id' => $supplyDept->id,
                'role' => RolesEnum::SUPPLY->value,
                'description' => 'Manages supply operations'
            ],
            [
                'name' => 'Sales Manager',
                'email' => 'sales@gentlewalker.com',
                'password' => 'admin123!',
                'department_id' => $salesDept->id,
                'role' => RolesEnum::SALESMANAGER->value,
                'description' => 'Manages sales operations'
            ],
            [
                'name' => 'Warehouse Manager',
                'email' => 'warehouse@gentlewalker.com',
                'password' => 'admin123!',
                'department_id' => $warehouseDept->id,
                'role' => RolesEnum::WAREHOUSEMANAGER->value,
                'description' => 'Manages warehouse operations'
            ],
            [
                'name' => 'Finance Manager',
                'email' => 'finance@gentlewalker.com',
                'password' => 'admin123!',
                'department_id' => $financeDept->id,
                'role' => RolesEnum::FINANCEMANAGER->value,
                'description' => 'Manages financial operations'
            ],
            [
                'name' => 'Customer Service',
                'email' => 'customerservice@gentlewalker.com',
                'password' => 'admin123!',
                'department_id' => $customerServiceDept->id,
                'role' => RolesEnum::CUSTOMERSERVICE->value,
                'description' => 'Handles customer inquiries'
            ],
            [
                'name' => 'Production Manager',
                'email' => 'production@gentlewalker.com',
                'password' => 'admin123!',
                'department_id' => $productionDept->id,
                'role' => RolesEnum::PRODUCTIONMANAGER->value,
                'description' => 'Manages production planning'
            ],
            [
                'name' => 'Quality Control',
                'email' => 'quality@gentlewalker.com',
                'password' => 'admin123!',
                'department_id' => $qualityDept->id,
                'role' => RolesEnum::QUALITYCONTROL->value,
                'description' => 'Manages quality assurance'
            ],
            [
                'name' => 'Shipping Coordinator',
                'email' => 'shipping@gentlewalker.com',
                'password' => 'admin123!',
                'department_id' => $shippingDept->id,
                'role' => RolesEnum::SHIPPINGCOORDINATOR->value,
                'description' => 'Manages shipping operations'
            ],
            [
                'name' => 'Department Head',
                'email' => 'depthead@gentlewalker.com',
                'password' => 'admin123!',
                'department_id' => $adminDept->id,
                'role' => RolesEnum::DEPARTMENTHEAD->value,
                'description' => 'Manages department operations'
            ],
            [
                'name' => 'Auditor',
                'email' => 'auditor@gentlewalker.com',
                'password' => 'admin123!',
                'department_id' => $auditDept->id,
                'role' => RolesEnum::AUDITOR->value,
                'description' => 'Reviews system activities'
            ],
            [
                'name' => 'Viewer',
                'email' => 'viewer@gentlewalker.com',
                'password' => 'admin123!',
                'department_id' => $adminDept->id,
                'role' => RolesEnum::VIEWER->value,
                'description' => 'Read-only access'
            ],
        ];

        foreach ($users as $userData) {
            $user = User::firstOrCreate(
                ['email' => $userData['email']],
                [
                    'name' => $userData['name'],
                    'password' => Hash::make($userData['password']),
                    'email_verified_at' => now(),
                    'department_id' => $userData['department_id'],
                ]
            );

            // Only assign role if user doesn't already have it
            if (!$user->hasRole($userData['role'])) {
                $user->assignRole($userData['role']);
            }
            
            echo "User processed: {$userData['name']} ({$userData['email']}) - {$userData['role']}\n";
        }

        echo "\nAll users created successfully!\n";
    }
} 