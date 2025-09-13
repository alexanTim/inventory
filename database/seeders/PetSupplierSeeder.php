<?php

namespace Database\Seeders;

use App\Models\Supplier;
use Illuminate\Database\Seeder;

class PetSupplierSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $petSuppliers = [
            [
                'name' => 'PetFood Pro Distributors',
                'address' => '123 Pet Supply Lane, Metro Manila, Philippines',
                'contact_num' => '+63 2 8123 4567',
                'tin_num' => '123-456-789-000'
            ],
            [
                'name' => 'Furry Friends Supplies Co.',
                'address' => '456 Animal Care Road, Quezon City, Philippines',
                'contact_num' => '+63 2 8234 5678',
                'tin_num' => '234-567-890-000'
            ],
            [
                'name' => 'AquaPet Solutions',
                'address' => '789 Fish Tank Street, Makati, Philippines',
                'contact_num' => '+63 2 8345 6789',
                'tin_num' => '345-678-901-000'
            ],
            [
                'name' => 'Canine & Feline Essentials',
                'address' => '321 Dog Cat Avenue, Pasig, Philippines',
                'contact_num' => '+63 2 8456 7890',
                'tin_num' => '456-789-012-000'
            ],
            [
                'name' => 'Exotic Pet Supplies Ltd.',
                'address' => '654 Reptile Road, Taguig, Philippines',
                'contact_num' => '+63 2 8567 8901',
                'tin_num' => '567-890-123-000'
            ],
            [
                'name' => 'Small Animal Paradise',
                'address' => '987 Hamster Highway, Mandaluyong, Philippines',
                'contact_num' => '+63 2 8678 9012',
                'tin_num' => '678-901-234-000'
            ],
            [
                'name' => 'Bird & Fish World',
                'address' => '147 Aviary Lane, San Juan, Philippines',
                'contact_num' => '+63 2 8789 0123',
                'tin_num' => '789-012-345-000'
            ],
            [
                'name' => 'Pet Grooming Supplies Inc.',
                'address' => '258 Grooming Garden, Marikina, Philippines',
                'contact_num' => '+63 2 8890 1234',
                'tin_num' => '890-123-456-000'
            ],
            [
                'name' => 'Pet Health & Wellness Co.',
                'address' => '369 Veterinary Village, Caloocan, Philippines',
                'contact_num' => '+63 2 8901 2345',
                'tin_num' => '901-234-567-000'
            ],
            [
                'name' => 'Pet Accessories Unlimited',
                'address' => '741 Collar Corner, Malabon, Philippines',
                'contact_num' => '+63 2 9012 3456',
                'tin_num' => '012-345-678-000'
            ]
        ];

        foreach ($petSuppliers as $supplier) {
            Supplier::updateOrCreate(
                ['name' => $supplier['name']],
                $supplier
            );
        }
    }
} 