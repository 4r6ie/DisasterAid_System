<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\EvacuationCenter;

class EvacuationCenterFactory extends Factory
{
    protected $model = EvacuationCenter::class;

    public function definition(): array
    {
        $statuses = ['active', 'inactive', 'full', 'maintenance'];
        $facilities = ['water', 'electricity', 'toilets', 'medical', 'kitchen', 'sleeping_area', 'shower', 'laundry'];

        return [
            'name' => $this->faker->company() . ' Evacuation Center',
            'address' => $this->faker->streetAddress(),
            'latitude' => $this->faker->latitude(5.5, 21.5),
            'longitude' => $this->faker->longitude(116, 127),
            'capacity' => $this->faker->numberBetween(50, 500),
            'current_occupancy' => $this->faker->numberBetween(0, 500),
            'status' => $this->faker->randomElement($statuses),
            'facilities' => $this->faker->randomElements($facilities, $this->faker->numberBetween(3, 6)),
            'contact_person' => $this->faker->name(),
            'contact_phone' => $this->faker->phoneNumber(),
            'contact_email' => $this->faker->safeEmail(),
            'notes' => $this->faker->optional()->paragraph(),
        ];
    }
}