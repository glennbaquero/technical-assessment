<?php

use App\Enums\TodoStatus;
use App\Models\User;

test('guests cannot access todos routes', function () {
    $response = $this->get(route('todos.index'));

    $response->assertRedirect(route('login'));
});

test('todos index page is displayed', function () {
    $user = User::factory()->create();
    $user->todos()->create(['title' => 'Buy groceries']);

    $response = $this
        ->actingAs($user)
        ->get(route('todos.index'));

    $response->assertOk();
});

test('todos index only shows the authenticated user\'s todos', function () {
    $user = User::factory()->create();
    $otherUser = User::factory()->create();

    $ownTodo = $user->todos()->create(['title' => 'Mine']);
    $otherUser->todos()->create(['title' => 'Not mine']);

    $response = $this
        ->actingAs($user)
        ->get(route('todos.index'));

    $response->assertInertia(
        fn ($page) => $page
            ->component('todos/index')
            ->has('todos', 1)
            ->where('todos.0.id', $ownTodo->id)
    );
});

test('todo can be created', function () {
    $user = User::factory()->create();

    $response = $this
        ->actingAs($user)
        ->post(route('todos.store'), [
            'title' => 'Buy groceries',
            'description' => 'Milk, eggs, bread',
        ]);

    $response
        ->assertSessionHasNoErrors()
        ->assertRedirect(route('todos.index'));

    expect($user->todos()->count())->toBe(1);

    $todo = $user->todos()->first();

    expect($todo->title)->toBe('Buy groceries');
    expect($todo->description)->toBe('Milk, eggs, bread');
    expect($todo->status)->toBe(TodoStatus::Pending);
});

test('todo creation requires a title', function () {
    $user = User::factory()->create();

    $response = $this
        ->actingAs($user)
        ->post(route('todos.store'), ['title' => '']);

    $response->assertSessionHasErrors('title');
});

test('todo description is optional', function () {
    $user = User::factory()->create();

    $response = $this
        ->actingAs($user)
        ->post(route('todos.store'), ['title' => 'Just a title']);

    $response->assertSessionHasNoErrors();
    expect($user->todos()->first()->description)->toBeNull();
});

test('todo can be updated', function () {
    $user = User::factory()->create();
    $todo = $user->todos()->create(['title' => 'Old title']);

    $response = $this
        ->actingAs($user)
        ->patch(route('todos.update', $todo), [
            'title' => 'New title',
            'description' => 'New description',
        ]);

    $response
        ->assertSessionHasNoErrors()
        ->assertRedirect(route('todos.index'));

    $todo->refresh();

    expect($todo->title)->toBe('New title');
    expect($todo->description)->toBe('New description');
});

test('updating a todo preserves its completion status', function () {
    $user = User::factory()->create();
    $todo = $user->todos()->create(['title' => 'Old title']);
    $todo->markComplete();

    $this
        ->actingAs($user)
        ->patch(route('todos.update', $todo), ['title' => 'New title']);

    $todo->refresh();

    expect($todo->status)->toBe(TodoStatus::Completed);
    expect($todo->completed_at)->not->toBeNull();
});

test('todo can be deleted', function () {
    $user = User::factory()->create();
    $todo = $user->todos()->create(['title' => 'Delete me']);

    $response = $this
        ->actingAs($user)
        ->delete(route('todos.destroy', $todo));

    $response->assertRedirect(route('todos.index'));

    expect($user->todos()->count())->toBe(0);
});

test('todo can be toggled from pending to completed', function () {
    $user = User::factory()->create();
    $todo = $user->todos()->create(['title' => 'Toggle me']);

    $response = $this
        ->actingAs($user)
        ->patch(route('todos.toggle', $todo));

    $response->assertRedirect(route('todos.index'));

    $todo->refresh();

    expect($todo->status)->toBe(TodoStatus::Completed);
    expect($todo->completed_at)->not->toBeNull();
});

test('todo can be toggled from completed to pending', function () {
    $user = User::factory()->create();
    $todo = $user->todos()->create(['title' => 'Toggle me back']);
    $todo->markComplete();

    $this
        ->actingAs($user)
        ->patch(route('todos.toggle', $todo));

    $todo->refresh();

    expect($todo->status)->toBe(TodoStatus::Pending);
    expect($todo->completed_at)->toBeNull();
});

test('user cannot update another user\'s todo', function () {
    $user = User::factory()->create();
    $otherUser = User::factory()->create();
    $todo = $otherUser->todos()->create(['title' => 'Not yours']);

    $response = $this
        ->actingAs($user)
        ->patch(route('todos.update', $todo), ['title' => 'Hijacked']);

    $response->assertForbidden();
});

test('user cannot delete another user\'s todo', function () {
    $user = User::factory()->create();
    $otherUser = User::factory()->create();
    $todo = $otherUser->todos()->create(['title' => 'Not yours']);

    $response = $this
        ->actingAs($user)
        ->delete(route('todos.destroy', $todo));

    $response->assertForbidden();
});

test('user cannot toggle another user\'s todo', function () {
    $user = User::factory()->create();
    $otherUser = User::factory()->create();
    $todo = $otherUser->todos()->create(['title' => 'Not yours']);

    $response = $this
        ->actingAs($user)
        ->patch(route('todos.toggle', $todo));

    $response->assertForbidden();
});
