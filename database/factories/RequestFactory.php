<?php

namespace Database\Factories;

use App\Models\Request;
use Illuminate\Database\Eloquent\Factories\Factory;

class RequestFactory extends Factory
{
    // Указываем, что эта фабрика для модели Request
    protected $model = Request::class;

    public function definition(): array
    {
        return [
            'clientName' => $this->faker->name(),
            'phone' => $this->faker->phoneNumber(),
            'address' => $this->faker->address(),
            'problemText' => $this->faker->sentence(10),
            'status' => $this->faker->randomElement(['new', 'assigned', 'in_progress', 'done', 'canceled']),
            'assignedTo' => null,
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
