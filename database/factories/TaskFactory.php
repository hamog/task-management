<?php

namespace Database\Factories;

use App\Enums\TaskStatus;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class TaskFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    protected static ?string $password;

    public function definition(): array
    {
        return [
            'title' => $this->faker->title(),
            'description' => $this->faker->paragraph(),
            'status' => array_rand(TaskStatus::values()),
            'started_at' => null,
            'finished_at' => null,
            'user_id' => User::query()->first()->id
        ];
    }
}
