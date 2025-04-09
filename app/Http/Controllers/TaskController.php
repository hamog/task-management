<?php

namespace App\Http\Controllers;

use App\Http\Requests\Task\StoreRequest;
use App\Models\Organization;
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
                    'started_at' => $task->started_at ? $task->started_at->format('Y-m-d H:i:s') : '-',
                    'finished_at' => $task->finished_at ? $task->finished_at->format('Y-m-d H:i:s') : '-',
                    'created_at' => $task->created_at->format('Y-m-d H:i:s'),
                ]),
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('Task/Create');
    }

    public function store(StoreRequest $request): RedirectResponse
    {
        Auth::user()->tasks()->create($request->validated());

        return Redirect::route('tasks.index')->with('success', 'Task created.');
    }

    public function edit(Organization $organization): Response
    {
        return Inertia::render('Organizations/Edit', [
            'organization' => [
                'id' => $organization->id,
                'name' => $organization->name,
                'email' => $organization->email,
                'phone' => $organization->phone,
                'address' => $organization->address,
                'city' => $organization->city,
                'region' => $organization->region,
                'country' => $organization->country,
                'postal_code' => $organization->postal_code,
                'deleted_at' => $organization->deleted_at,
                'contacts' => $organization->contacts()->orderByName()->get()->map->only('id', 'name', 'city', 'phone'),
            ],
        ]);
    }

    public function update(Organization $organization): RedirectResponse
    {
        $organization->update(
            Request::validate([
                'name' => ['required', 'max:100'],
                'email' => ['nullable', 'max:50', 'email'],
                'phone' => ['nullable', 'max:50'],
                'address' => ['nullable', 'max:150'],
                'city' => ['nullable', 'max:50'],
                'region' => ['nullable', 'max:50'],
                'country' => ['nullable', 'max:2'],
                'postal_code' => ['nullable', 'max:25'],
            ])
        );

        return Redirect::back()->with('success', 'Organization updated.');
    }

    public function destroy(Organization $organization): RedirectResponse
    {
        $organization->delete();

        return Redirect::back()->with('success', 'Organization deleted.');
    }

    public function restore(Organization $organization): RedirectResponse
    {
        $organization->restore();

        return Redirect::back()->with('success', 'Organization restored.');
    }
}
