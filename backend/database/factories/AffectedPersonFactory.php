<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\AffectedPerson;

class AffectedPersonFactory extends Factory
{
    protected $model = AffectedPerson::class;

    public function definition(): array
    {
        $conditions = ['safe', 'injured', 'critical', 'missing', 'deceased', 'rescued'];
        $genders = ['male', 'female', 'other'];

        return [
            'name' => $this->faker->name(),
            'age' => $this->faker->numberBetween(0, 100),
            'gender' => $this->faker->randomElement($genders),
            'contact_phone' => $this->faker->optional()->phoneNumber(),
            'address' => $this->faker->optional()->address(),
            'condition' => $this->faker->randomElement($conditions),
            'medical_notes' => $this->faker->optional()->paragraph(),
            'family_contact_name' => $this->faker->optional()->name(),
            'family_contact_phone' => $this->faker->optional()->phoneNumber(),
            'notes' => $this->faker->optional()->paragraph(),
        ];
    }
}