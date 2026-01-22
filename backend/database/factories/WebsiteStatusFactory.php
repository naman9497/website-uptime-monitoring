<?php

namespace Database\Factories;

use App\Models\Website;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\WebsiteStatus>
 */
class WebsiteStatusFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'website_id' => Website::factory(),
            'current_status' => $this->faker->randomElement(['up', 'down', 'unknown']),
            'last_checked_at' => $this->faker->dateTimeBetween('-1 hour', 'now'),
            'alert_sent' => false,
            'alert_sent_at' => null,
        ];
    }

    public function up(): static
    {
        return $this->state(fn (array $attributes) => [
            'current_status' => 'up',
        ]);
    }

    public function down(): static
    {
        return $this->state(fn (array $attributes) => [
            'current_status' => 'down',
        ]);
    }

    public function withAlertSent(): static
    {
        return $this->state(fn (array $attributes) => [
            'alert_sent' => true,
            'alert_sent_at' => $this->faker->dateTimeBetween('-1 hour', 'now'),
        ]);
    }
}
