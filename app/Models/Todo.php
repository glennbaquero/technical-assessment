<?php

namespace App\Models;

use App\Enums\TodoStatus;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property int $user_id
 * @property string $title
 * @property string|null $description
 * @property TodoStatus $status
 * @property Carbon|null $due_date
 * @property Carbon|null $completed_at
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */
#[Fillable(['title', 'description', 'status', 'due_date'])]
class Todo extends Model
{
    protected function casts(): array
    {
        return [
            'status' => TodoStatus::class,
            'due_date' => 'date',
            'completed_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function markComplete(): void
    {
        $this->forceFill([
            'status' => TodoStatus::Completed,
            'completed_at' => now(),
        ])->save();
    }

    public function markPending(): void
    {
        $this->forceFill([
            'status' => TodoStatus::Pending,
            'completed_at' => null,
        ])->save();
    }
}
