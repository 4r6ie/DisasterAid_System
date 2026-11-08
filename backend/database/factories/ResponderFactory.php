<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Responder;

class ResponderFactory extends Factory
{
    protected $model = Responder::class;

    public function definition(): array
    {
        $specializations = ['medical', 'fire', 'rescue', 'police', 'logistics', 'coordination', 'communications'];
        $ranks = ['chief', 'captain', 'lieutenant', 'sergeant', 'officer', 'volunteer'];
        $statuses = ['available', 'on_duty', 'off_duty', 'on_leave', 'inactive'];
        $certifications = ['EMT', 'Firefighter I', 'Firefighter II', 'Swift Water Rescue', 'High Angle Rescue', 'ICS-100', 'ICS-200', 'ICS-300', 'ICS-400', 'HazMat Awareness', 'First Aid', 'CPR', 'BLS', 'ACLS'];

        return [
            'employee_id' => 'EMP-' . str_pad($this->faker->unique()->numberBetween(1, 9999), 4, '0', STR_PAD_LEFT),
            'name' => $this->faker->name(),
            'email' => $this->faker->unique()->safeEmail(),
            'phone' => $this->faker->phoneNumber(),
            'specialization' => $this->faker->randomElement($specializations),
            'rank' => $this->faker->randomElement($ranks),
            'status' => $this->faker->randomElement($statuses),
            'address' => $this->faker->address(),
            'date_of_birth' => $this->faker->dateTimeBetween('-55 years', '-22 years'),
            'certifications' => $this->faker->randomElements($certifications, $this->faker->numberBetween(1, 5)),
            'equipment' => $this->faker->optional()->randomElements(['Radio', 'GPS', 'First Aid Kit', 'Rescue Rope', 'Helmet', 'Boots', 'Uniform'], $this->faker->numberBetween(1, 4)),
            'hired_at' => $this->faker->dateTimeBetween('-20 years', 'now'),
        ];
    }
}