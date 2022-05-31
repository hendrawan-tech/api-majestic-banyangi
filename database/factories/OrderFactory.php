<?php

namespace Database\Factories;

use App\Models\Order;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\Factory;

class OrderFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Order::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'code' => $this->faker->text(255),
            'quantity' => $this->faker->text(255),
            'total' => $this->faker->randomFloat(2, 0, 9999),
            'date' => $this->faker->date,
            'status' => 'Menunggu Pembayaran',
            'product_id' => \App\Models\Product::factory(),
            'user_id' => \App\Models\User::factory(),
            'payment_id' => \App\Models\Payment::factory(),
        ];
    }
}
