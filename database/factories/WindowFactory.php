<?php

namespace Database\Factories;

use App\Models\Window;
use App\Models\Tender;
use App\Models\WindowTemplate;
use Illuminate\Database\Eloquent\Factories\Factory;

class WindowFactory extends Factory
{
    protected $model = Window::class;

    public function definition(): array
    {
        return [
            'tender_id' => Tender::factory(),
            'template_id' => WindowTemplate::factory(),
            'label' => $this->faker->optional()->words(2, true),
            'width_mm' => $this->faker->numberBetween(600, 2000),
            'height_mm' => $this->faker->numberBetween(800, 1800),
            'notes' => $this->faker->optional()->sentence(),
        ];
    }

    public function withLabel(string $label): static
    {
        return $this->state(fn (array $attributes) => [
            'label' => $label,
        ]);
    }

    public function standardSize(): static
    {
        return $this->state(fn (array $attributes) => [
            'width_mm' => 1200,
            'height_mm' => 1400,
        ]);
    }

    public function largeSize(): static
    {
        return $this->state(fn (array $attributes) => [
            'width_mm' => 1800,
            'height_mm' => 1600,
        ]);
    }

    public function smallSize(): static
    {
        return $this->state(fn (array $attributes) => [
            'width_mm' => 800,
            'height_mm' => 1000,
        ]);
    }
}