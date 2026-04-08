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
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => static::$password ??= Hash::make('password'),
            'remember_token' => Str::random(10),
            'role' => 'cashier', // Default role (changed from 'user' to 'cashier' because 'user' doesn't exist in ENUM)
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }

    /**
     * Set the user role to admin.
     */
    public function admin(): static
    {
        return $this->state(fn (array $attributes) => [
            'role' => 'admin',
        ]);
    }

    /**
     * Set the user role to super admin.
     */
    public function superAdmin(): static
    {
        return $this->state(fn (array $attributes) => [
            'role' => 'super_admin',
        ]);
    }

    /**
     * Set the user role to super admin global.
     */
    public function superAdminGlobal(): static
    {
        return $this->state(fn (array $attributes) => [
            'role' => 'super_admin_global',
        ]);
    }

    /**
     * Set the user role to manager (instead of stock_manager).
     */
    public function stockManager(): static
    {
        return $this->state(fn (array $attributes) => [
            'role' => 'manager', // Changed from 'stock_manager' to 'manager' (exists in ENUM)
        ]);
    }

    /**
     * Set the user role to storekeeper.
     */
    public function storekeeper(): static
    {
        return $this->state(fn (array $attributes) => [
            'role' => 'storekeeper',
        ]);
    }

    /**
     * Set the user role to cashier.
     */
    public function cashier(): static
    {
        return $this->state(fn (array $attributes) => [
            'role' => 'cashier',
        ]);
    }
}