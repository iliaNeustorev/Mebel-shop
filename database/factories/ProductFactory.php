<?php

namespace Database\Factories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->word(3, true),
            'description' => $this->faker->sentence(10),
            'price' => $this->faker->randomFloat(2, 1, 666666,66),
            'picture' => 'nopicture.png',
            'category_id' => Category::get()->random()->id,            
        ];
    }
}
