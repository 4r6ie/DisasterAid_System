<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\ReliefItem;

class ReliefItemFactory extends Factory
{
    protected $model = ReliefItem::class;

    public function definition(): array
    {
        $categories = ['food', 'water', 'medicine', 'shelter', 'clothing', 'hygiene', 'tools', 'other'];
        $units = ['units', 'kg', 'liters', 'packs', 'boxes', 'bottles', 'cans'];
        $statuses = ['active', 'inactive', 'discontinued'];

        return [
            'name' => $this->faker->word() . ' ' . $this->faker->randomElement(['Pack', 'Kit', 'Box', 'Bundle', 'Set']),
            'category' => $this->faker->randomElement($categories),
            'unit' => $this->faker->randomElement($units),
            'quantity' => $this->faker->numberBetween(0, 1000),
            'min_stock' => $this->faker->numberBetween(10, 100),
            'unit_cost' => $this->faker->randomFloat(2, 10, 500),
            'location' => $this->faker->optional()->warehouse(),
            'expiry_date' => $this->faker->optional()->dateTimeBetween('now', '+2 years'),
            'batch_number' => 'BATCH-' . strtoupper($this->faker->bothify('??-####')),
            'description' => $this->faker->optional()->paragraph(),
            'status' => $this->faker->randomElement($statuses),
        ];
    }
}