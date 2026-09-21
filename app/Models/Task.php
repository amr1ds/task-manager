<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Task extends Model
{
    use HasFactory;

    public const STATUSES = ['todo', 'in_progress', 'done'];
    public const PRIORITIES = ['low', 'medium', 'high'];

    protected $fillable = [
        'project_id',
        'title',
        'description',
        'status',
        'priority',
        'deadline',
        'creator_id',
        'assignee_id',
    ];

    protected function casts(): array
    {
        return [
            'deadline' => 'date',
        ];
    }

    /* ----------------------- Связи ----------------------- */

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'creator_id');
    }

    public function assignee(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assignee_id');
    }

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class)->latest();
    }

    /* ----------------------- Хелперы для отображения ----------------------- */

    // Человекочитаемые названия статусов (для бейджей в Bootstrap)
    public function statusLabel(): string
    {
        return match ($this->status) {
            'todo' => 'К выполнению',
            'in_progress' => 'В работе',
            'done' => 'Выполнено',
            default => $this->status,
        };
    }

    // CSS-класс бейджа Bootstrap под статус
    public function statusBadge(): string
    {
        return match ($this->status) {
            'todo' => 'bg-secondary',
            'in_progress' => 'bg-info text-dark',
            'done' => 'bg-success',
            default => 'bg-light text-dark',
        };
    }

    public function priorityLabel(): string
    {
        return match ($this->priority) {
            'low' => 'Низкий',
            'medium' => 'Средний',
            'high' => 'Высокий',
            default => $this->priority,
        };
    }

    public function priorityBadge(): string
    {
        return match ($this->priority) {
            'low' => 'bg-success',
            'medium' => 'bg-warning text-dark',
            'high' => 'bg-danger',
            default => 'bg-light text-dark',
        };
    }
}
