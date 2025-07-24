<?php

namespace Database\Factories;

use App\Models\WindowTemplate;
use Illuminate\Database\Eloquent\Factories\Factory;

class WindowTemplateFactory extends Factory
{
    protected $model = WindowTemplate::class;

    public function definition(): array
    {
        $templates = [
            'Single Fixed Panel',
            'Double Casement',
            'Triple Casement',
            'Sliding Window 2-Panel',
            'Sliding Window 3-Panel',
            'Awning Window',
            'Hopper Window',
            'Double Hung',
            'Tilt & Turn',
            'Bay Window 3-Panel',
            'Bow Window 5-Panel',
        ];

        return [
            'name' => $this->faker->randomElement($templates),
        ];
    }

    public function singlePanel(): static
    {
        return $this->state(fn (array $attributes) => [
            'name' => 'Single Fixed Panel',
        ]);
    }

    public function doubleCasement(): static
    {
        return $this->state(fn (array $attributes) => [
            'name' => 'Double Casement',
        ]);
    }

    public function slidingTwoPanel(): static
    {
        return $this->state(fn (array $attributes) => [
            'name' => 'Sliding Window 2-Panel',
        ]);
    }
}