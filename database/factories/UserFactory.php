<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'full_name' => fake()->name(),
            'username' => fake()->unique()->userName(),
            'email' => fake()->unique()->safeEmail(),
            // Generate nomor HP acak (format 62)
            'phone_number' => '628' . fake()->unique()->numerify('##########'),
            'password_hash' => static::$password ??= Hash::make('password'),
            'role' => 'user',
            'status' => 'active', // Set active agar bisa langsung login saat testing
            'last_login_at' => now(),
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            // Opsional, karena kita tidak pakai email_verified_at di schema utama,
            // tapi biarkan saja atau hapus method ini jika tidak perlu.
        ]);
    }
}
