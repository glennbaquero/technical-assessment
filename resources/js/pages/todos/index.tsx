import { Form, Head } from '@inertiajs/react';
import { Check } from 'lucide-react';
import { useState } from 'react';
import TodoController from '@/actions/App/Http/Controllers/TodoController';
import Heading from '@/components/heading';
import InputError from '@/components/input-error';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { cn } from '@/lib/utils';
import { index } from '@/routes/todos';
import type { Todo } from '@/types';

const textareaClassName =
    'border-input placeholder:text-muted-foreground flex w-full rounded-md border bg-transparent px-3 py-2 text-base shadow-xs outline-none focus-visible:border-ring focus-visible:ring-[3px] focus-visible:ring-ring/50 md:text-sm';

export default function TodosIndex({ todos }: { todos: Todo[] }) {
    const [editingId, setEditingId] = useState<number | null>(null);

    return (
        <>
            <Head title="Todos" />

            <div className="space-y-6">
                <Heading
                    title="Todos"
                    description="Create and manage your tasks"
                />

                <Form
                    {...TodoController.store.form()}
                    resetOnSuccess
                    className="space-y-4 rounded-lg border p-4"
                >
                    {({ processing, errors }) => (
                        <>
                            <div className="grid gap-2">
                                <Label htmlFor="title">Title</Label>
                                <Input
                                    id="title"
                                    name="title"
                                    required
                                    placeholder="What needs to be done?"
                                    data-test="todo-title-input"
                                />
                                <InputError message={errors.title} />
                            </div>

                            <div className="grid gap-2">
                                <Label htmlFor="description">Description</Label>
                                <textarea
                                    id="description"
                                    name="description"
                                    rows={2}
                                    placeholder="Optional details"
                                    className={textareaClassName}
                                    data-test="todo-description-input"
                                />
                                <InputError message={errors.description} />
                            </div>

                            <Button
                                disabled={processing}
                                data-test="create-todo-button"
                            >
                                Add todo
                            </Button>
                        </>
                    )}
                </Form>

                {todos.length === 0 ? (
                    <p
                        className="text-sm text-muted-foreground"
                        data-test="todos-empty-state"
                    >
                        You don't have any todos yet. Add one above to get
                        started.
                    </p>
                ) : (
                    <ul className="space-y-3" data-test="todos-list">
                        {todos.map((todo) => (
                            <li
                                key={todo.id}
                                className="rounded-lg border p-4"
                                data-test={`todo-item-${todo.id}`}
                            >
                                {editingId === todo.id ? (
                                    <Form
                                        {...TodoController.update.form(todo.id)}
                                        onSuccess={() => setEditingId(null)}
                                        className="space-y-3"
                                    >
                                        {({ processing, errors }) => (
                                            <>
                                                <div className="grid gap-2">
                                                    <Label
                                                        htmlFor={`title-${todo.id}`}
                                                    >
                                                        Title
                                                    </Label>
                                                    <Input
                                                        id={`title-${todo.id}`}
                                                        name="title"
                                                        required
                                                        defaultValue={
                                                            todo.title
                                                        }
                                                    />
                                                    <InputError
                                                        message={errors.title}
                                                    />
                                                </div>

                                                <div className="grid gap-2">
                                                    <Label
                                                        htmlFor={`description-${todo.id}`}
                                                    >
                                                        Description
                                                    </Label>
                                                    <textarea
                                                        id={`description-${todo.id}`}
                                                        name="description"
                                                        rows={2}
                                                        defaultValue={
                                                            todo.description ??
                                                            ''
                                                        }
                                                        className={
                                                            textareaClassName
                                                        }
                                                    />
                                                    <InputError
                                                        message={
                                                            errors.description
                                                        }
                                                    />
                                                </div>

                                                <div className="flex gap-2">
                                                    <Button
                                                        disabled={processing}
                                                        data-test={`save-todo-${todo.id}`}
                                                    >
                                                        Save
                                                    </Button>
                                                    <Button
                                                        type="button"
                                                        variant="outline"
                                                        onClick={() =>
                                                            setEditingId(null)
                                                        }
                                                    >
                                                        Cancel
                                                    </Button>
                                                </div>
                                            </>
                                        )}
                                    </Form>
                                ) : (
                                    <div className="flex items-start gap-3">
                                        <Form
                                            {...TodoController.toggle.form(
                                                todo.id,
                                            )}
                                            options={{ preserveScroll: true }}
                                        >
                                            {({ processing }) => (
                                                <button
                                                    type="submit"
                                                    disabled={processing}
                                                    aria-label={
                                                        todo.status ===
                                                        'completed'
                                                            ? 'Mark as incomplete'
                                                            : 'Mark as complete'
                                                    }
                                                    data-test={`toggle-todo-${todo.id}`}
                                                    className={cn(
                                                        'mt-0.5 flex size-5 shrink-0 items-center justify-center rounded border transition-colors',
                                                        todo.status ===
                                                            'completed'
                                                            ? 'border-primary bg-primary text-primary-foreground'
                                                            : 'border-input bg-transparent',
                                                    )}
                                                >
                                                    {todo.status ===
                                                        'completed' && (
                                                        <Check className="size-3.5" />
                                                    )}
                                                </button>
                                            )}
                                        </Form>

                                        <div className="flex-1 space-y-1">
                                            <p
                                                className={
                                                    todo.status === 'completed'
                                                        ? 'font-medium text-muted-foreground line-through'
                                                        : 'font-medium'
                                                }
                                            >
                                                {todo.title}
                                            </p>

                                            {todo.description && (
                                                <p className="text-sm text-muted-foreground">
                                                    {todo.description}
                                                </p>
                                            )}

                                            <p className="text-xs text-muted-foreground">
                                                {todo.status === 'completed'
                                                    ? 'Completed'
                                                    : 'Pending'}{' '}
                                                · Created{' '}
                                                {new Date(
                                                    todo.created_at,
                                                ).toLocaleDateString()}
                                            </p>
                                        </div>

                                        <div className="flex gap-2">
                                            <Button
                                                type="button"
                                                variant="outline"
                                                size="sm"
                                                onClick={() =>
                                                    setEditingId(todo.id)
                                                }
                                                data-test={`edit-todo-${todo.id}`}
                                            >
                                                Edit
                                            </Button>

                                            <Form
                                                {...TodoController.destroy.form(
                                                    todo.id,
                                                )}
                                            >
                                                {({ processing }) => (
                                                    <Button
                                                        type="submit"
                                                        variant="destructive"
                                                        size="sm"
                                                        disabled={processing}
                                                        onClick={(event) => {
                                                            if (
                                                                !confirm(
                                                                    'Delete this todo?',
                                                                )
                                                            ) {
                                                                event.preventDefault();
                                                            }
                                                        }}
                                                        data-test={`delete-todo-${todo.id}`}
                                                    >
                                                        Delete
                                                    </Button>
                                                )}
                                            </Form>
                                        </div>
                                    </div>
                                )}
                            </li>
                        ))}
                    </ul>
                )}
            </div>
        </>
    );
}

TodosIndex.layout = {
    breadcrumbs: [
        {
            title: 'Todos',
            href: index(),
        },
    ],
};
