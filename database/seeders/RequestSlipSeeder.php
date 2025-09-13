<?php

namespace Database\Seeders;

use App\Models\RequestSlip;
use App\Models\Department;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RequestSlipSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Clear existing request slips
        RequestSlip::truncate();

        $purposes = [
            'Pet Food',
            'Pet Toys', 
            'Pet Care',
            'Pet Health',
            'Pet Grooming',
            'Pet Bedding',
            'Pet Training',
            'Pet Safety',
            'Office Supplies',
            'Packaging',
            'Equipment',
            'Other'
        ];

        $statuses = ['pending', 'approved', 'rejected'];
        
        $departments = Department::all();
        $users = User::all();

        if ($departments->isEmpty() || $users->isEmpty()) {
            $this->command->warn('No departments or users found. Skipping RequestSlip seeding.');
            return;
        }

        $descriptions = [
            'Request for premium dog food supplies for the pet food distribution center.',
            'Need cat toys and accessories for retail inventory replenishment.',
            'Pet care products including shampoos, conditioners, and grooming tools.',
            'Medical supplies for pet health including vitamins and supplements.',
            'Professional grooming equipment for pet salon operations.',
            'Comfortable pet bedding and sleeping accessories for retail.',
            'Training supplies including leashes, collars, and training treats.',
            'Pet safety equipment including gates, harnesses, and monitoring devices.',
            'Office supplies for administrative operations and record keeping.',
            'Packaging materials for product shipping and storage.',
            'Equipment maintenance and replacement parts for warehouse operations.',
            'Miscellaneous items needed for daily operations and special projects.'
        ];

        for ($i = 0; $i < 20; $i++) {
            $purpose = $purposes[array_rand($purposes)];
            $descriptionIndex = array_search($purpose, $purposes);
            
            RequestSlip::create([
                'status' => $statuses[array_rand($statuses)],
                'purpose' => $purpose,
                'description' => $descriptions[$descriptionIndex] ?? $descriptions[array_rand($descriptions)],
                'request_date' => now()->subDays(rand(1, 180)),
                'sent_from' => $departments->random()->id,
                'sent_to' => $departments->random()->id,
                'requested_by' => $users->random()->id,
                'approver' => null,
                'created_at' => now()->subDays(rand(1, 180)),
                'updated_at' => now()->subDays(rand(1, 180)),
            ]);
        }

        $this->command->info('RequestSlip seeder completed successfully!');
    }
} 