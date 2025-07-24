<?php

namespace Database\Factories;

use App\Models\TemplateCell;
use App\Models\WindowTemplate;
use Illuminate\Database\Eloquent\Factories\Factory;

class TemplateCellFactory extends Factory
{
    protected $model = TemplateCell::class;

    public function definition(): array
    {
        return [
            'template_id' => WindowTemplate::factory(),
            'cell_index' => $this->faker->numberBetween(0, 5),
            'x' => $this->faker->randomFloat(4, 0, 1),
            'y' => $this->faker->randomFloat(4, 0, 1),
            'width_ratio' => $this->faker->randomFloat(4, 0.1, 1),
            'height_ratio' => $this->faker->randomFloat(4, 0.1, 1),
        ];
    }

    public function leftHalf(): static
    {
        return $this->state(fn (array $attributes) => [
            'cell_index' => 0,
            'x' => 0.0000,
            'y' => 0.0000,
            'width_ratio' => 0.5000,
            'height_ratio' => 1.0000,
        ]);
    }

    public function rightHalf(): static
    {
        return $this->state(fn (array $attributes) => [
            'cell_index' => 1,
            'x' => 0.5000,
            'y' => 0.0000,
            'width_ratio' => 0.5000,
            'height_ratio' => 1.0000,
        ]);
    }

    public function fullPanel(): static
    {
        return $this->state(fn (array $attributes) => [
            'cell_index' => 0,
            'x' => 0.0000,
            'y' => 0.0000,
            'width_ratio' => 1.0000,
            'height_ratio' => 1.0000,
        ]);
    }

    public function leftThird(): static
    {
        return $this->state(fn (array $attributes) => [
            'cell_index' => 0,
            'x' => 0.0000,
            'y' => 0.0000,
            'width_ratio' => 0.3333,
            'height_ratio' => 1.0000,
        ]);
    }

    public function centerThird(): static
    {
        return $this->state(fn (array $attributes) => [
            'cell_index' => 1,
            'x' => 0.3333,
            'y' => 0.0000,
            'width_ratio' => 0.3334,
            'height_ratio' => 1.0000,
        ]);
    }

    public function rightThird(): static
    {
        return $this->state(fn (array $attributes) => [
            'cell_index' => 2,
            'x' => 0.6667,
            'y' => 0.0000,
            'width_ratio' => 0.3333,
            'height_ratio' => 1.0000,
        ]);
    }
}