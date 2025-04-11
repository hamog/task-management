<?php

namespace Feature;

use App\Enums\TaskStatus;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;

class TaskTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user =  User::factory()->create([
            'name' => 'user',
            'email' => 'user@example.com',
            'password' => 'password',
        ]);

        $this->user->tasks()->createMany([
            [
                'title' => 'task1',
                'description' => 'task1 description',
                'status' => TaskStatus::PENDING->value,
                'started_at' => today(),
                'finished_at' => today()->addDays(3)
            ], [
                'title' => 'task2',
                'description' => 'task2 description',
                'status' => TaskStatus::COMPLETED->value,
                'started_at' => today()->addDays(4),
                'finished_at' => today()->addDays(5)
            ],
        ]);
    }

    public function test_can_view_tasks(): void
    {
        $this->actingAs($this->user)
            ->get('/user/tasks')
            ->assertInertia(fn (Assert $assert) => $assert
                ->component('Task/Index')
                ->has('tasks.data', 2)
                ->has('tasks.data.0', fn (Assert $assert) => $assert
                    ->has('id')
                    ->where('title', 'task1')
                    ->where('description', 'task1 description')
                    ->where('status', TaskStatus::PENDING->value)
                )
                ->has('tasks.data.1', fn (Assert $assert) => $assert
                    ->has('id')
                    ->where('title', 'task2')
                    ->where('description', 'task2 description')
                    ->where('status', TaskStatus::COMPLETED->value)
                )
            );
    }

    public function test_can_search_for_tasks(): void
    {
        $this->actingAs($this->user)
            ->get('/tasks?search=task1')
            ->assertInertia(fn (Assert $assert) => $assert
                ->component('Task/Index')
                ->where('filters.search', 'task1')
                ->has('tasks.data', 1)
                ->has('tasks.data.0', fn (Assert $assert) => $assert
                    ->has('id')
                    ->where('title', 'task1')
                    ->where('description', 'task1 description')
                    ->where('status', TaskStatus::PENDING->value)
                )
            );
    }
}
