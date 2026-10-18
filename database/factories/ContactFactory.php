<?php

namespace Database\Factories;

use App\Models\Contact;
use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Contact>
 */
class ContactFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            //シーディング要件を満たす
            'category_id' => Category::inRandomOrder()->value('id'),
            'first_name' => fake()->name(),
            'last_name' => fake()->name(),
            'gender' => fake()->randomElement(['1', '2', '3']),
            'email' => fake()->email(),
            'tel' => fake()->numerify('0##########'),
            'address' => fake()->address(),
            'building' => fake()->text(30),
            'detail' => fake()->sentence(),
        ];
    }
}
