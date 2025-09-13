<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DepartmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $departments = [
            ['name' => 'Admin Department', 'description' => 'Manages overall flow of the pet goods distribution company.'],
            ['name' => 'Purchase Department', 'description' => 'Handles purchase orders and vendor management for pet supplies.'],
            ['name' => 'Supply Department', 'description' => 'Handles procurement and supply chain management for pet products.'],
            ['name' => 'Warehouse Department', 'description' => 'Manages pet goods inventory and distribution.'],
            ['name' => 'Sales Department', 'description' => 'Handles customer sales and pet product distribution.'], 
            ['name' => 'Finance Department', 'description' => 'Manages financial operations for pet goods business.'],
            ['name' => 'Customer Service Department', 'description' => 'Provides customer support for pet product inquiries.'],
            ['name' => 'Production Department', 'description' => 'Manages production planning for pet goods.'],
            ['name' => 'Quality Control Department', 'description' => 'Ensures quality standards for pet products.'],
            ['name' => 'Shipping Department', 'description' => 'Handles shipping and logistics for pet goods.'],
            ['name' => 'Audit Department', 'description' => 'Conducts audits and compliance for pet goods operations.'],
        ];

        foreach ($departments as $department) {
            \App\Models\Department::firstOrCreate(
                ['name' => $department['name']],
                $department
            );
        }
    }
}
