<?php

namespace App\Http\Controllers;

use App\Http\Requests\TaskStoreRequest;
use App\Http\Requests\TaskUpdateRequest;
use App\Models\Task;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Request;
use Inertia\Inertia;
use Inertia\Response;

class TaskController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('Task/Index', [
            'filters' => Request::all('search'),
            'tasks' => Auth::user()->tasks()
                ->orderBy('id', 'desc')
                ->filter(Request::only('search'))
                ->paginate(10)
                ->withQueryString()
                ->through(fn ($task) => [
                    'id' => $task->id,
                    'title' => $task->title,
                    'status' => $task->status,
                    'started_at' => $task->started_at ? $task->started_at->format('Y-m-d') : '-',
                    'finished_at' => $task->finished_at ? $task->finished_at->format('Y-m-d') : '-',
                    'created_at' => $task->created_at->format('Y-m-d H:i'),
                ]),
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('Task/Create');
    }

    public function store(TaskStoreRequest $request): RedirectResponse
    {
        Auth::user()->tasks()->create($request->validated());

        return Redirect::route('tasks.index')->with('success', 'Task created.');
    }

    public function edit(Task $task): Response
    {
        if (Auth::user()->cannot('update', $task)) {
            abort(403);
        }

        return Inertia::render('Task/Edit', [
            'task' => [
                'id' => $task->id,
                'title' => $task->title,
                'description' => $task->description,
                'status' => $task->status,
                'started_at' => $task->started_at ? $task->started_at->format('Y-m-d') : null,
                'finished_at' => $task->finished_at ? $task->finished_at->format('Y-m-d') : null,
            ],
        ]);
    }

    public function update(TaskUpdateRequest $request, Task $task): RedirectResponse
    {
        $task->update($request->validated());

        return Redirect::route('tasks.index')->with('success', 'Task updated.');
    }

    public function destroy(Task $task): RedirectResponse
    {
        if (Auth::user()->cannot('delete', $task)) {
            abort(403);
        }

        $task->delete();

        return Redirect::route('tasks.index')->with('success', 'Task deleted.');
    }
}
