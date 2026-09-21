<?php

namespace App\Policies;

use App\Models\Task;
use App\Models\User;

class TaskPolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    // Видеть задачу: персонал (админ/менеджер) или назначенный исполнитель
    public function view(User $user, Task $task): bool
    {
        return $user->isStaff() || $user->id === $task->assignee_id;
    }

    // Создавать задачи могут админ и менеджер
    public function create(User $user): bool
    {
        return $user->isStaff();
    }

    // Полное редактирование задачи — только персонал
    public function update(User $user, Task $task): bool
    {
        return $user->isStaff();
    }

    public function delete(User $user, Task $task): bool
    {
        return $user->isStaff();
    }

    // Менять статус может персонал ИЛИ назначенный исполнитель
    public function updateStatus(User $user, Task $task): bool
    {
        return $user->isStaff() || $user->id === $task->assignee_id;
    }

    // Комментировать может тот, кто видит задачу
    public function comment(User $user, Task $task): bool
    {
        return $this->view($user, $task);
    }
}
