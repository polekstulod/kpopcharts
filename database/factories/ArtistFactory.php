<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Artist>
 */
class ArtistFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'code' => $this->faker->unique()->numberBetween(1000, 9999),
            'email' => $this->faker->unique()->safeEmail(),
            'debut' => $this->faker->date(),
            'description' => $this->faker->sentence(),
            'image' => $this->faker->imageUrl(),
        ];
    }
}
