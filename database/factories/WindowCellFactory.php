<?php

namespace Database\Factories;

use App\Models\WindowCell;
use App\Models\Window;
use App\Models\TemplateCell;
use Illuminate\Database\Eloquent\Factories\Factory;

class WindowCellFactory extends Factory
{
    protected $model = WindowCell::class;

    public function definition(): array
    {
        return [
            'window_id' => Window::factory(),
            'template_cell_id' => TemplateCell::factory(),
            'open_left' => $this->faker->boolean(30),
            'open_right' => $this->faker->boolean(30),
            'open_top' => $this->faker->boolean(20),
            'open_bottom' => $this->faker->boolean(20),
            'notes' => $this->faker->optional()->sentence(),
        ];
    }

    public function noOpenings(): static
    {
        return $this->state(fn (array $attributes) => [
            'open_left' => false,
            'open_right' => false,
            'open_top' => false,
            'open_bottom' => false,
        ]);
    }

    public function leftOpening(): static
    {
        return $this->state(fn (array $attributes) => [
            'open_left' => true,
            'open_right' => false,
            'open_top' => false,
            'open_bottom' => false,
        ]);
    }

    public function rightOpening(): static
    {
        return $this->state(fn (array $attributes) => [
            'open_left' => false,
            'open_right' => true,
            'open_top' => false,
            'open_bottom' => false,
        ]);
    }

    public function topOpening(): static
    {
        return $this->state(fn (array $attributes) => [
            'open_left' => false,
            'open_right' => false,
            'open_top' => true,
            'open_bottom' => false,
        ]);
    }

    public function bottomOpening(): static
    {
        return $this->state(fn (array $attributes) => [
            'open_left' => false,
            'open_right' => false,
            'open_top' => false,
            'open_bottom' => true,
        ]);
    }

    public function multipleOpenings(): static
    {
        return $this->state(fn (array $attributes) => [
            'open_left' => $this->faker->boolean(70),
            'open_right' => $this->faker->boolean(70),
            'open_top' => $this->faker->boolean(50),
            'open_bottom' => $this->faker->boolean(50),
        ]);
    }
}