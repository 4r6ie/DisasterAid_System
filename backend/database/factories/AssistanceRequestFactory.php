<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\AssistanceRequest;
use App\Models\User;

class AssistanceRequestFactory extends Factory
{
    protected $model = AssistanceRequest::class;

    public function definition(): array
    {
        $types = ['food', 'water', 'medicine', 'shelter', 'clothing', 'other'];
        $urgencies = ['low', 'medium', 'high', 'critical'];
        $statuses = ['pending', 'approved', 'dispatched', 'completed', 'rejected', 'cancelled'];

        return [
            'request_number' => 'REQ-' . now()->year . '-' . str_pad($this->faker->unique()->numberBetween(1, 9999), 4, '0', STR_PAD_LEFT),
            'requested_by' => User::factory(),
            'assistance_type' => $this->faker->randomElement($types),
            'quantity' => $this->faker->numberBetween(1, 100),
            'unit' => $this->faker->randomElement(['units', 'kg', 'liters', 'packs', 'boxes']),
            'urgency' => $this->faker->randomElement($urgencies),
            'status' => $this->faker->randomElement($statuses),
            'description' => $this->faker->paragraph(),
            'latitude' => $this->faker->optional()->latitude(5.5, 21.5),
            'longitude' => $this->faker->optional()->longitude(116, 127),
            'address' => $this->faker->optional()->streetAddress(),
            'contact_name' => $this->faker->optional()->name(),
            'contact_phone' => $this->faker->optional()->phoneNumber(),
            'metadata' => null,
        ];
    }
}