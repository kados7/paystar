<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Arr;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Cart>
 */
class CartFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'number'=> $this->faker->creditCardNumber(),
            'name'=> $this->faker->name,
            'bank'=> $this->faker->name,
            // Arr::random('ملی','ملت','صادرات','آینده','مسکن'),
        ];
    }
}
