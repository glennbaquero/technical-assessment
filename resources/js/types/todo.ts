export type TodoStatus = 'pending' | 'completed';

export type Todo = {
    id: number;
    user_id: number;
    title: string;
    description: string | null;
    status: TodoStatus;
    due_date: string | null;
    completed_at: string | null;
    created_at: string;
    updated_at: string;
    [key: string]: unknown;
};
