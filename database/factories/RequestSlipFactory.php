<?php

namespace Database\Factories;

use App\Models\Department;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\RequestSlip>
 */
class RequestSlipFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'status' => $this->faker->randomElement(['pending', 'approved', 'rejected']),
            'purpose' => $this->faker->randomElement([
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
            ]),
            'description' => $this->faker->paragraph,
            'request_date' => $this->faker->dateTimeBetween('-6 months', 'now'),
            'sent_from' => Department::inRandomOrder()->first()?->id ?? Department::factory(),
            'sent_to' => Department::inRandomOrder()->first()?->id ?? Department::factory(),
            'requested_by' => User::inRandomOrder()->first()?->id ?? User::factory(),
            'approver' => null,
        ];
    }
}
