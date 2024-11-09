<?php

namespace Database\Factories;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends Factory
 */
class UserFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'username' => 'u1',
            'password' => Hash::make('1234'),
            'full_name' => 'user1',
            'gender' => 'male',
            'birthdate' => Carbon::now(),
            'phone_number' => '0987654321',
            'governorate' => 'Damascus',
            'city' => 'mazzah',
            'street' => '86',
            'landline' => null,
            'rate' => null,
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     *
     * @return static
     */
    public function unverified()
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }
}
