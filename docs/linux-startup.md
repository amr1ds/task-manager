# Запуск на Linux

Текущий composer.lock требует **PHP не ниже 8.4.1** (зависимости Symfony 8.1). Используйте PHP 8.4 с актуальными исправлениями и Composer 2. Памятка с требованием PHP 8.2+ не соответствует этому lock-файлу. PHP веб-сервера и командной строки могут отличаться.

Нужны расширения PHP, перечисленные Composer, и pdo_sqlite/sqlite3. Названия пакетов зависят от дистрибутива. Не переносите vendor с другого компьютера и не используйте --ignore-platform-reqs.

## Новая локальная копия

Команды выполняются по очереди. При ошибке остановитесь и разберите её, не продолжайте следующую команду.

```bash
git clone https://github.com/amr1ds/task-manager.git task-manager-check
cd task-manager-check
php -v
composer check-platform-reqs --lock
cp .env.example .env
mkdir -p storage/framework/cache/data storage/framework/sessions
mkdir -p storage/framework/views storage/logs bootstrap/cache
touch database/database.sqlite
composer install
php artisan key:generate
php artisan config:clear
php artisan migrate --seed
php artisan optimize:clear
php artisan serve --host=127.0.0.1 --port=8000
```

Откройте http://127.0.0.1:8000 на том же компьютере. Если composer check-platform-reqs сообщает о несовместимости, сначала исправьте версию PHP или расширения. .env.example уже выбирает SQLite; в новой копии можно оставить DB_DATABASE закомментированным.

Локальные демонстрационные логины: admin@example.com, manager@example.com, executor@example.com. Пароль у всех password. Не используйте эти учётные записи на публичном сервере.

Основные страницы используют Bootstrap CDN, поэтому npm/Vite для этого запуска не нужны.

## Уже существующая копия

Не перезаписывайте .env и существующий APP_KEY. Генерация ключа нужна, если он отсутствует. Проверьте DB_CONNECTION и путь SQLite: Windows-путь не подходит Linux. Используйте обычный `php artisan migrate`, а сидирование выполняйте только при необходимости: текущий сидер повторно создаёт уникальные email. `migrate:fresh` удаляет данные и не является обычным исправлением 500.

Laravel должен иметь право записи в storage и bootstrap/cache, SQLite — в файл базы и его родительский каталог. Для artisan это пользователь терминала, для PHP-FPM — пользователь его процесса. Не выдавайте 777 всему проекту. Корень сайта Nginx/Apache должен указывать на public.

## Ошибка 500

```bash
php -v
composer check-platform-reqs --lock
tail -n 80 storage/logs/laravel.log
```

- `No application encryption key has been specified`: отсутствует APP_KEY; для новой копии key:generate, затем config:clear.
- `no such table`: миграции не выполнены или подключена другая база. Сессии по умолчанию тоже хранятся в БД.
- `could not find driver`: нет PDO-драйвера выбранной БД.
- `Permission denied` / `readonly database`: неверные права владельца процесса.
- `requires PHP >=8.4.1`: несовместимый PHP, в том числе отдельный PHP-FPM.
- Если журнал не создаётся, смотрите терминал artisan serve и журналы PHP-FPM/Apache/Nginx.

Не отправляйте .env или секреты вместе с диагностикой.

## Проверка

```bash
php artisan test
```

Tests/Feature/ProjectVerificationTest.php проверяет CRUD проектов, права, валидацию и владельца; ApplicationPagesTest.php — страницы с демонстрационными данными для трёх ролей. Тесты используют отдельную SQLite-базу в памяти по phpunit.xml. Linux application checks повторяет установку, миграции, компиляцию шаблонов, тесты и запуск HTTP-сервера на Ubuntu в GitHub Actions. Результат конкретного запуска смотрите во вкладке Actions, а не определяйте по наличию файла workflow.
