<?php

namespace Database\Seeders;

use App\Models\Customer;
use Illuminate\Database\Seeder;

class CustomerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Clear existing customers (handle foreign key constraints)
        Customer::query()->delete();

        $customers = [
            [
                'name' => 'Pet Paradise Store',
                'address' => '123 Main Street, Quezon City, Metro Manila',
                'contact_num' => '+63 912 345 6789',
                'tin_num' => '123-456-789-000',
            ],
            [
                'name' => 'Furry Friends Pet Shop',
                'address' => '456 Pet Avenue, Makati City, Metro Manila',
                'contact_num' => '+63 923 456 7890',
                'tin_num' => '234-567-890-000',
            ],
            [
                'name' => 'Happy Tails Veterinary Clinic',
                'address' => '789 Animal Road, Taguig City, Metro Manila',
                'contact_num' => '+63 934 567 8901',
                'tin_num' => '345-678-901-000',
            ],
            [
                'name' => 'Pawsome Pet Supplies',
                'address' => '321 Bark Street, Pasig City, Metro Manila',
                'contact_num' => '+63 945 678 9012',
                'tin_num' => '456-789-012-000',
            ],
            [
                'name' => 'Pet Care Center',
                'address' => '654 Meow Lane, Mandaluyong City, Metro Manila',
                'contact_num' => '+63 956 789 0123',
                'tin_num' => '567-890-123-000',
            ],
            [
                'name' => 'Animal Kingdom Store',
                'address' => '987 Pet Plaza, San Juan City, Metro Manila',
                'contact_num' => '+63 967 890 1234',
                'tin_num' => '678-901-234-000',
            ],
            [
                'name' => 'Pet Wellness Hub',
                'address' => '147 Health Street, Marikina City, Metro Manila',
                'contact_num' => '+63 978 901 2345',
                'tin_num' => '789-012-345-000',
            ],
            [
                'name' => 'Furry Family Pet Shop',
                'address' => '258 Family Road, Caloocan City, Metro Manila',
                'contact_num' => '+63 989 012 3456',
                'tin_num' => '890-123-456-000',
            ],
            [
                'name' => 'Pet Essentials Store',
                'address' => '369 Essential Avenue, Malabon City, Metro Manila',
                'contact_num' => '+63 990 123 4567',
                'tin_num' => '901-234-567-000',
            ],
            [
                'name' => 'Animal Care Plus',
                'address' => '741 Care Street, Navotas City, Metro Manila',
                'contact_num' => '+63 901 234 5678',
                'tin_num' => '012-345-678-000',
            ],
            [
                'name' => 'Pet World Outlet',
                'address' => '852 World Road, Valenzuela City, Metro Manila',
                'contact_num' => '+63 912 345 6789',
                'tin_num' => '123-456-789-001',
            ],
            [
                'name' => 'Furry Friends Forever',
                'address' => '963 Forever Lane, Parañaque City, Metro Manila',
                'contact_num' => '+63 923 456 7890',
                'tin_num' => '234-567-890-001',
            ],
            [
                'name' => 'Pet Health Solutions',
                'address' => '159 Health Avenue, Las Piñas City, Metro Manila',
                'contact_num' => '+63 934 567 8901',
                'tin_num' => '345-678-901-001',
            ],
            [
                'name' => 'Animal Lovers Store',
                'address' => '357 Love Street, Muntinlupa City, Metro Manila',
                'contact_num' => '+63 945 678 9012',
                'tin_num' => '456-789-012-001',
            ],
            [
                'name' => 'Pet Supply Depot',
                'address' => '486 Depot Road, Pateros, Metro Manila',
                'contact_num' => '+63 956 789 0123',
                'tin_num' => '567-890-123-001',
            ],
        ];

        foreach ($customers as $customer) {
            Customer::firstOrCreate(
                ['tin_num' => $customer['tin_num']],
                $customer
            );
        }

        $this->command->info('Customer seeder completed successfully!');
    }
} 