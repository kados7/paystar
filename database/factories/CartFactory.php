<?php

namespace Database\Factories;

use App\Models\User;
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
            'user_id'=> User::factory(),
            'number'=> $this->faker->creditCardNumber(),
            'name'=> $this->faker->name,
            'bank'=> Arr::random(['ملی','ملت','صادرات','آینده','مسکن']),
            // Arr::random('ملی','ملت','صادرات','آینده','مسکن'),
        ];
    }
}
