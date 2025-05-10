<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Attendance>
 */
class AttendanceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'check_in_time' => now()->subHours(rand(1, 8)),
            'check_out_time' => null,
        ];
    }

    /**
     * Indicate that the attendance record is checked out.
     *
     * @return static
     */
    public function checkedOut(): static
    {
        return $this->state(fn (array $attributes) => [
            'check_out_time' => now(),
        ]);
    }
}
