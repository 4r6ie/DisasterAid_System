<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\DisasterIncident;
use App\Models\User;

class DisasterIncidentFactory extends Factory
{
    protected $model = DisasterIncident::class;

    public function definition(): array
    {
        $disasterTypes = ['earthquake', 'flood', 'typhoon', 'fire', 'landslide', 'volcanic', 'tsunami', 'other'];
        $severities = ['low', 'moderate', 'high', 'critical'];
        $statuses = ['reported', 'verified', 'active', 'responding', 'contained', 'resolved'];

        return [
            'incident_id' => 'DIS-' . now()->year . '-' . str_pad($this->faker->unique()->numberBetween(1, 999), 3, '0', STR_PAD_LEFT),
            'reported_by' => User::factory(),
            'disaster_type' => $this->faker->randomElement($disasterTypes),
            'title' => $this->faker->sentence(3),
            'description' => $this->faker->paragraph(),
            'severity' => $this->faker->randomElement($severities),
            'status' => $this->faker->randomElement($statuses),
            'latitude' => $this->faker->latitude(5.5, 21.5), // Philippines bounds
            'longitude' => $this->faker->longitude(116, 127),
            'address' => $this->faker->streetAddress(),
            'city' => $this->faker->city(),
            'province' => $this->faker->state(),
            'affected_count' => $this->faker->numberBetween(0, 1000),
            'injured_count' => $this->faker->numberBetween(0, 100),
            'missing_count' => $this->faker->numberBetween(0, 50),
            'deceased_count' => $this->faker->numberBetween(0, 20),
            'rescued_count' => $this->faker->numberBetween(0, 200),
            'occurred_at' => $this->faker->dateTimeBetween('-1 year', 'now'),
            'metadata' => null,
        ];
    }
}