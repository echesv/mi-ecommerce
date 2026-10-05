<?php

namespace Database\Factories;

use App\Models\Payment;
use App\Models\Order;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Payment>
 */
class PaymentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            //
            'order_id' => Order::factory(),
            'stripe_payment_id' => 'pi_' . $this->faker->unique()->bothify('????????????'),
            'amount' => $this->faker->numberBetween(10000, 100000) / 100,
            'status' => $this->faker->randomElement(['pending', 'succeeded', 'failed', 'refunded']),
            'stripe_response' => json_encode([
                'id' => 'pi_' . $this->faker->unique()->bothify('????????????'),
                'object' => 'payment_intent',
                'status' => 'succeeded',
            ]),
            'processed_at' => $this->faker->optional()->dateTime(),
        ];
    }
}
