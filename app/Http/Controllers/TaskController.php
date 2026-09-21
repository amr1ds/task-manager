<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TaskController extends Controller
{
    public function index(Request $request): View
    {
        $this->authorize('viewAny', Task::class);

        $query = Task::with(['project', 'assignee', 'creator']);

        // Исполнитель видит только свои назначенные задачи
        if ($request->user()->isExecutor()) {
            $query->where('assignee_id', $request->user()->id);
        }

        // --- Фильтры ---
        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }
        if ($request->filled('priority')) {
            $query->where('priority', $request->input('priority'));
        }
        if ($request->filled('project_id')) {
            $query->where('project_id', $request->input('project_id'));
        }

        // --- Сортировка ---
        $sort = $request->input('sort', 'created_at');
        $direction = $request->input('direction', 'desc');
        if (! in_array($sort, ['created_at', 'deadline', 'priority', 'status'], true)) {
            $sort = 'created_at';
        }
        if (! in_array($direction, ['asc', 'desc'], true)) {
            $direction = 'desc';
        }
        $query->orderBy($sort, $direction);

        $tasks = $query->paginate(10)->withQueryString();
        $projects = Project::orderBy('name')->get();

        return view('tasks.index', compact('tasks', 'projects'));
    }

    public function create(): View
    {
        $this->authorize('create', Task::class);

        $projects = Project::orderBy('name')->get();
        // Исполнителями могут быть любые пользователи
        $users = User::orderBy('name')->get();

        return view('tasks.create', compact('projects', 'users'));
    }

    public function store(StoreTaskRequest $request): RedirectResponse
    {
        $this->authorize('create', Task::class);

        $task = Task::create([
            ...$request->validated(),
            'creator_id' => $request->user()->id,
        ]);

        return redirect()
            ->route('tasks.show', $task)
            ->with('success', 'Задача создана.');
    }

    public function show(Task $task): View
    {
        $this->authorize('view', $task);

        $task->load(['project', 'assignee', 'creator', 'comments.author']);

        return view('tasks.show', compact('task'));
    }

    public function edit(Task $task): View
    {
        $this->authorize('update', $task);

        $projects = Project::orderBy('name')->get();
        $users = User::orderBy('name')->get();

        return view('tasks.edit', compact('task', 'projects', 'users'));
    }

    public function update(UpdateTaskRequest $request, Task $task): RedirectResponse
    {
        $this->authorize('update', $task);

        $task->update($request->validated());

        return redirect()
            ->route('tasks.show', $task)
            ->with('success', 'Задача обновлена.');
    }

    public function destroy(Task $task): RedirectResponse
    {
        $this->authorize('delete', $task);

        $task->delete();

        return redirect()
            ->route('tasks.index')
            ->with('success', 'Задача удалена.');
    }

    /**
     * Быстрая смена статуса задачи.
     * Доступна персоналу и назначенному исполнителю (TaskPolicy::updateStatus).
     */
    public function updateStatus(Request $request, Task $task): RedirectResponse
    {
        $this->authorize('updateStatus', $task);

        $data = $request->validate([
            'status' => ['required', 'in:' . implode(',', Task::STATUSES)],
        ]);

        $task->update(['status' => $data['status']]);

        return back()->with('success', 'Статус задачи обновлён.');
    }
}
