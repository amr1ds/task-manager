<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    // Список доступных ролей (используется в формах и валидации)
    public const ROLES = ['admin', 'manager', 'executor'];

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /* ----------------------- Хелперы по ролям ----------------------- */

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isManager(): bool
    {
        return $this->role === 'manager';
    }

    public function isExecutor(): bool
    {
        return $this->role === 'executor';
    }

    // Удобная проверка: пользователь — менеджер ИЛИ админ
    public function isStaff(): bool
    {
        return in_array($this->role, ['admin', 'manager'], true);
    }

    /* ----------------------- Связи ----------------------- */

    // Проекты, которые создал пользователь
    public function projects(): HasMany
    {
        return $this->hasMany(Project::class);
    }

    // Задачи, которые поставил пользователь
    public function createdTasks(): HasMany
    {
        return $this->hasMany(Task::class, 'creator_id');
    }

    // Задачи, назначенные на пользователя
    public function assignedTasks(): HasMany
    {
        return $this->hasMany(Task::class, 'assignee_id');
    }
}
