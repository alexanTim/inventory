<?php

namespace Database\Seeders;

use App\Models\Supplier;
use Illuminate\Database\Seeder;

class SupplierSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Clear existing suppliers (handle foreign key constraints)
        Supplier::query()->delete();

        $suppliers = [
            [
                'name' => 'Pet Food Manufacturers Inc.',
                'address' => '123 Manufacturing Blvd, Laguna Technopark, Laguna',
                'contact_num' => '+63 912 345 6789',
                'tin_num' => 'SUP-001-456-789-000',
            ],
            [
                'name' => 'Premium Pet Supplies Co.',
                'address' => '456 Supply Street, Cavite Industrial Zone, Cavite',
                'contact_num' => '+63 923 456 7890',
                'tin_num' => 'SUP-002-567-890-000',
            ],
            [
                'name' => 'Veterinary Products Ltd.',
                'address' => '789 Medical Avenue, Batangas Industrial Park, Batangas',
                'contact_num' => '+63 934 567 8901',
                'tin_num' => 'SUP-003-678-901-000',
            ],
            [
                'name' => 'Pet Toy Distributors',
                'address' => '321 Toy Road, Pampanga Industrial Complex, Pampanga',
                'contact_num' => '+63 945 678 9012',
                'tin_num' => 'SUP-004-789-012-000',
            ],
            [
                'name' => 'Pet Care Essentials Corp.',
                'address' => '654 Care Lane, Bulacan Industrial Estate, Bulacan',
                'contact_num' => '+63 956 789 0123',
                'tin_num' => 'SUP-005-890-123-000',
            ],
            [
                'name' => 'Animal Health Solutions',
                'address' => '987 Health Street, Rizal Industrial Zone, Rizal',
                'contact_num' => '+63 967 890 1234',
                'tin_num' => 'SUP-006-901-234-000',
            ],
            [
                'name' => 'Pet Grooming Supplies Inc.',
                'address' => '147 Grooming Avenue, Quezon Industrial Park, Quezon',
                'contact_num' => '+63 978 901 2345',
                'tin_num' => 'SUP-007-012-345-000',
            ],
            [
                'name' => 'Pet Safety Equipment Co.',
                'address' => '258 Safety Road, Nueva Ecija Industrial Complex, Nueva Ecija',
                'contact_num' => '+63 989 012 3456',
                'tin_num' => 'SUP-008-123-456-000',
            ],
            [
                'name' => 'Pet Training Supplies Ltd.',
                'address' => '369 Training Street, Tarlac Industrial Estate, Tarlac',
                'contact_num' => '+63 990 123 4567',
                'tin_num' => 'SUP-009-234-567-000',
            ],
            [
                'name' => 'Pet Bedding Manufacturers',
                'address' => '741 Bedding Avenue, Pangasinan Industrial Zone, Pangasinan',
                'contact_num' => '+63 901 234 5678',
                'tin_num' => 'SUP-010-345-678-000',
            ],
            [
                'name' => 'Pet Packaging Solutions',
                'address' => '852 Packaging Road, Zambales Industrial Park, Zambales',
                'contact_num' => '+63 912 345 6789',
                'tin_num' => 'SUP-011-456-789-000',
            ],
            [
                'name' => 'Pet Equipment Suppliers',
                'address' => '963 Equipment Lane, Bataan Industrial Complex, Bataan',
                'contact_num' => '+63 923 456 7890',
                'tin_num' => 'SUP-012-567-890-000',
            ],
            [
                'name' => 'Pet Import Export Corp.',
                'address' => '159 Import Street, Manila Port Area, Manila',
                'contact_num' => '+63 934 567 8901',
                'tin_num' => 'SUP-013-678-901-000',
            ],
            [
                'name' => 'Pet Wholesale Distributors',
                'address' => '357 Wholesale Avenue, Pasay Commercial District, Pasay',
                'contact_num' => '+63 945 678 9012',
                'tin_num' => 'SUP-014-789-012-000',
            ],
            [
                'name' => 'Pet Retail Suppliers',
                'address' => '486 Retail Road, Caloocan Commercial Zone, Caloocan',
                'contact_num' => '+63 956 789 0123',
                'tin_num' => 'SUP-015-890-123-000',
            ],
        ];

        foreach ($suppliers as $supplier) {
            Supplier::firstOrCreate(
                ['tin_num' => $supplier['tin_num']],
                $supplier
            );
        }

        $this->command->info('Supplier seeder completed successfully!');
    }
} 