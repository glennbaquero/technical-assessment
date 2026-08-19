# Caravea Todo App

A small Todo application built on the Laravel + React (Inertia) starter kit. Users sign up, log in, and manage their own private list of todos.

## Running it

Requirements: PHP 8.3+, Composer, Node 18+, npm.

```bash
composer install
cp .env.example .env
php artisan key:generate
touch database/database.sqlite
php artisan migrate --seed
npm install
npm run dev      # in one terminal
php artisan serve # in another
```

Or use the bundled scripts:

```bash
composer setup   # install, .env, key, migrate, npm install, npm build
composer dev     # runs the Laravel dev server + queue + Vite together
```

Log in with the seeded account: `test@example.com` / `password`.

### Tests & checks

```bash
composer test        # Pest test suite
```

## Why it's built this way

For ownership, I didn't want to rely on "the query happened to be scoped correctly" as the only thing stopping one user from touching another's todos. So there's an explicit `TodoPolicy` checking `user_id` on view/update/delete, and the controller calls `authorize()` before every mutation. It's a bit more code than trusting the query scope, but it's easy to point to and easy to test — and there are feature tests asserting cross-user access actually fails.

Status is an enum rather than a boolean because "pending vs completed" felt like it was one step away from needing a third state (like "archived") down the line, and I'd rather that be a non-event than a migration. It also gave completion status a natural home for a `label()` method instead of `$completed ? 'Done' : 'Pending'` sprinkled through the views. Along the same lines, I kept `completed_at` as its own column instead of inferring it from `updated_at`.

Validation rules for title/description live in a shared trait that both the store and update requests pull from.
