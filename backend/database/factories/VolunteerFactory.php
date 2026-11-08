<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Volunteer;

class VolunteerFactory extends Factory
{
    protected $model = Volunteer::class;

    public function definition(): array
    {
        $skills = ['first_aid', 'rescue', 'logistics', 'medical', 'communication', 'cooking', 'driving', 'search_rescue', 'firefighting', 'counseling'];
        $statuses = ['available', 'busy', 'inactive', 'on_leave'];

        $allSkills = $this->faker->randomElements($skills, $this->faker->numberBetween(1, 4));

        $availability = [];
        $days = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'];
        $periods = ['morning', 'afternoon', 'evening'];
        foreach ($days as $day) {
            $availability[$day] = $this->faker->randomElements($periods, $this->faker->numberBetween(0, 3));
        }

        return [
            'name' => $this->faker->name(),
            'email' => $this->faker->unique()->safeEmail(),
            'phone' => $this->faker->phoneNumber(),
            'address' => $this->faker->address(),
            'date_of_birth' => $this->faker->dateTimeBetween('-60 years', '-18 years'),
            'gender' => $this->faker->randomElement(['male', 'female', 'other']),
            'skills' => $allSkills,
            'availability' => $availability,
            'status' => $this->faker->randomElement($statuses),
            'emergency_contact_name' => $this->faker->name(),
            'emergency_contact_phone' => $this->faker->phoneNumber(),
            'notes' => $this->faker->optional()->paragraph(),
        ];
    }
}