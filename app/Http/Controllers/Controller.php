<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;

abstract class Controller
{
    // Подключаем трейты, чтобы в контроллерах работали $this->authorize(...)
    // и $this->validate(...). В Laravel 12 базовый контроллер по умолчанию пустой.
    use AuthorizesRequests, ValidatesRequests;
}
