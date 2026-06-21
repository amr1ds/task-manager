<?php

namespace App\Providers;

use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // Пагинация в стиле Bootstrap 5 (по умолчанию Laravel отдаёт Tailwind).
        Paginator::useBootstrapFive();

        // Gate: управление пользователями доступно только администратору.
        // Используется в админ-разделе (смена ролей, удаление пользователей).
        Gate::define('manage-users', function ($user) {
            return $user->isAdmin();
        });
    }
}
