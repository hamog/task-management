<?php

namespace Feature;

use App\Enums\TaskStatus;
use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;

class TaskTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user =  $this->user();

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
                    ->where('title', 'task2')
                    ->where('status', TaskStatus::COMPLETED->value)
                    ->where('started_at', today()->addDays(4)->format('Y-m-d'))
                    ->where('finished_at', today()->addDays(5)->format('Y-m-d'))
                    ->has('created_at')
                )
                ->has('tasks.data.1', fn (Assert $assert) => $assert
                    ->has('id')
                    ->where('title', 'task1')
                    ->where('status', TaskStatus::PENDING->value)
                    ->where('started_at', today()->format('Y-m-d'))
                    ->where('finished_at', today()->addDays(3)->format('Y-m-d'))
                    ->has('created_at')
                )
            );
    }

//    public function test_can_search_for_tasks(): void
//    {
//        $this->actingAs($this->user)
//            ->get('/user/tasks?search=task1')
//            ->assertInertia(fn (Assert $assert) => $assert
//                ->component('Task/Index')
//                ->where('filters.search', 'task2')
//                ->has('tasks.data', 1)
//                ->has('tasks.data.0', fn (Assert $assert) => $assert
//                    ->has('id')
//                    ->where('title', 'task2')
//                    ->where('status', TaskStatus::COMPLETED->value)
//                    ->where('started_at', today()->addDays(4)->format('Y-m-d'))
//                    ->where('finished_at', today()->addDays(5)->format('Y-m-d'))
//                    ->has('created_at')
//                )
//            );
//    }

    public function test_can_store_tasks()
    {
        $this->actingAs($this->user)
            ->post('/user/tasks', [
                'title' => 'task test',
                'description' => 'task test description',
                'status' => TaskStatus::PENDING->value,
                'started_at' => null,
                'finished_at' => null
            ])
            ->assertRedirect('/user/tasks')
            ->assertSessionHas('success', 'Task created.')
            ->assertSessionMissing('error');
    }

    public function test_can_update_tasks()
    {
        $task = Task::factory()->create();

        $this->actingAs($this->user)
            ->patch("/user/tasks/{$task->id}", [
                'title' => 'task test',
                'description' => 'task test description',
                'status' => TaskStatus::PENDING->value,
                'started_at' => null,
                'finished_at' => null
            ])
            ->assertRedirect('/user/tasks')
            ->assertSessionHas('success', 'Task updated.')
            ->assertSessionMissing('error');
    }

    public function test_can_delete_tasks()
    {
        $task = Task::factory()->create();

        $this->actingAs($this->user)
            ->delete("/user/tasks/{$task->id}")
            ->assertRedirect('/user/tasks')
            ->assertSessionHas('success', 'Task deleted.')
            ->assertSessionMissing('error');
    }
}
