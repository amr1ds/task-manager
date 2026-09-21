<?php

namespace Database\Seeders;

use App\Models\Comment;
use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // --- Пользователи с тремя ролями (пароль у всех: password) ---
        $admin = User::create([
            'name' => 'Администратор',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        $manager = User::create([
            'name' => 'Менеджер Иван',
            'email' => 'manager@example.com',
            'password' => Hash::make('password'),
            'role' => 'manager',
        ]);

        $executor = User::create([
            'name' => 'Исполнитель Пётр',
            'email' => 'executor@example.com',
            'password' => Hash::make('password'),
            'role' => 'executor',
        ]);

        // --- Проекты ---
        $project1 = Project::create([
            'name' => 'Сайт колледжа',
            'description' => 'Разработка нового сайта учебного заведения.',
            'user_id' => $manager->id,
        ]);

        $project2 = Project::create([
            'name' => 'Мобильное приложение',
            'description' => 'MVP мобильного приложения для студентов.',
            'user_id' => $admin->id,
        ]);

        // --- Задачи ---
        Task::create([
            'project_id' => $project1->id,
            'title' => 'Сверстать главную страницу',
            'description' => 'Адаптивная вёрстка на Bootstrap 5.',
            'status' => 'in_progress',
            'priority' => 'high',
            'deadline' => now()->addDays(7),
            'creator_id' => $manager->id,
            'assignee_id' => $executor->id,
        ]);

        Task::create([
            'project_id' => $project1->id,
            'title' => 'Настроить форму обратной связи',
            'description' => 'Валидация и отправка письма.',
            'status' => 'todo',
            'priority' => 'medium',
            'deadline' => now()->addDays(14),
            'creator_id' => $manager->id,
            'assignee_id' => $executor->id,
        ]);

        $task3 = Task::create([
            'project_id' => $project2->id,
            'title' => 'Спроектировать схему БД',
            'description' => 'ER-диаграмма основных сущностей.',
            'status' => 'done',
            'priority' => 'high',
            'deadline' => now()->subDays(2),
            'creator_id' => $admin->id,
            'assignee_id' => $executor->id,
        ]);

        // --- Комментарии ---
        Comment::create([
            'task_id' => $task3->id,
            'user_id' => $manager->id,
            'body' => 'Отличная работа, схему принял.',
        ]);

        Comment::create([
            'task_id' => $task3->id,
            'user_id' => $executor->id,
            'body' => 'Спасибо! Готов приступать к следующей задаче.',
        ]);
    }
}
