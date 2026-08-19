<?php

namespace App\Http\Controllers;

use App\Http\Requests\Todo\TodoStoreRequest;
use App\Http\Requests\Todo\TodoUpdateRequest;
use App\Models\Todo;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class TodoController extends Controller
{
    public function index(Request $request): Response
    {
        return Inertia::render('todos/index', [
            'todos' => $request->user()->todos()->latest()->get(),
        ]);
    }

    public function store(TodoStoreRequest $request): RedirectResponse
    {
        $request->user()->todos()->create($request->validated());

        Inertia::flash('toast', ['type' => 'success', 'message' => __('Todo created.')]);

        return to_route('todos.index');
    }

    public function update(TodoUpdateRequest $request, Todo $todo): RedirectResponse
    {
        $this->authorize('update', $todo);

        $todo->update($request->validated());

        Inertia::flash('toast', ['type' => 'success', 'message' => __('Todo updated.')]);

        return to_route('todos.index');
    }

    public function destroy(Todo $todo): RedirectResponse
    {
        $this->authorize('delete', $todo);

        $todo->delete();

        Inertia::flash('toast', ['type' => 'success', 'message' => __('Todo deleted.')]);

        return to_route('todos.index');
    }

    public function toggle(Todo $todo): RedirectResponse
    {
        $this->authorize('update', $todo);

        $todo->status->value === 'completed' ? $todo->markPending() : $todo->markComplete();

        return to_route('todos.index');
    }
}
