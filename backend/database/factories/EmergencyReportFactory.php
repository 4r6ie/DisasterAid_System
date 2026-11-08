<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\EmergencyReport;

class EmergencyReportFactory extends Factory
{
    protected $model = EmergencyReport::class;

    public function definition(): array
    {
        $types = ['medical', 'fire', 'rescue', 'evacuation', 'other'];
        $priorities = ['low', 'medium', 'high', 'critical'];
        $statuses = ['pending', 'assigned', 'in_progress', 'resolved', 'cancelled'];

        return [
            'emergency_type' => $this->faker->randomElement($types),
            'description' => $this->faker->paragraph(),
            'priority' => $this->faker->randomElement($priorities),
            'status' => $this->faker->randomElement($statuses),
            'latitude' => $this->faker->latitude(5.5, 21.5),
            'longitude' => $this->faker->longitude(116, 127),
            'address' => $this->faker->streetAddress(),
            'people_needing_help' => $this->faker->numberBetween(1, 50),
            'contact_name' => $this->faker->name(),
            'contact_phone' => $this->faker->phoneNumber(),
            'metadata' => null,
        ];
    }
}