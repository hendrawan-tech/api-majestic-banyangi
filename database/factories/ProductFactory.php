<?php

namespace Database\Factories;

use App\Models\Product;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Product::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'title' => $this->faker->sentence(10),
            'category' => 'Destinasi',
            'address' => $this->faker->address,
            'description' => $this->faker->sentence(15),
            'price' => $this->faker->randomFloat(2, 0, 9999),
        ];
    }
}
