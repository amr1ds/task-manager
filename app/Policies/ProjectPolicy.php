<?php

namespace App\Policies;

use App\Models\Project;
use App\Models\User;

class ProjectPolicy
{
    // Видеть список проектов могут все авторизованные
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Project $project): bool
    {
        return true;
    }

    // Создавать проекты могут админ и менеджер
    public function create(User $user): bool
    {
        return $user->isStaff();
    }

    // Редактировать: админ — любой, менеджер — только свой
    public function update(User $user, Project $project): bool
    {
        return $user->isAdmin() || $user->id === $project->user_id;
    }

    // Удалять: админ — любой, менеджер — только свой
    public function delete(User $user, Project $project): bool
    {
        return $user->isAdmin() || $user->id === $project->user_id;
    }
}
