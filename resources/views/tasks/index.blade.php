@extends('layouts.app')

@section('title', 'Задачи')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h3 class="mb-0">Задачи</h3>
    @can('create', App\Models\Task::class)
        <a href="{{ route('tasks.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-lg"></i> Новая задача
        </a>
    @endcan
</div>

{{-- Фильтры --}}
<div class="card shadow-sm mb-3">
    <div class="card-body">
        <form method="GET" action="{{ route('tasks.index') }}" class="row g-2 align-items-end">
            <div class="col-md-3">
                <label class="form-label">Проект</label>
                <select name="project_id" class="form-select">
                    <option value="">Все</option>
                    @foreach ($projects as $project)
                        <option value="{{ $project->id }}" @selected(request('project_id') == $project->id)>
                            {{ $project->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label">Статус</label>
                <select name="status" class="form-select">
                    <option value="">Все</option>
                    <option value="todo" @selected(request('status')==='todo')>К выполнению</option>
                    <option value="in_progress" @selected(request('status')==='in_progress')>В работе</option>
                    <option value="done" @selected(request('status')==='done')>Выполнено</option>
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label">Приоритет</label>
                <select name="priority" class="form-select">
                    <option value="">Любой</option>
                    <option value="low" @selected(request('priority')==='low')>Низкий</option>
                    <option value="medium" @selected(request('priority')==='medium')>Средний</option>
                    <option value="high" @selected(request('priority')==='high')>Высокий</option>
                </select>
            </div>
            <div class="col-md-3 d-flex gap-2">
                <button type="submit" class="btn btn-secondary w-100">Применить</button>
                <a href="{{ route('tasks.index') }}" class="btn btn-outline-secondary">Сброс</a>
            </div>
        </form>
    </div>
</div>

<div class="card shadow-sm">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th>Задача</th>
                    <th>Проект</th>
                    <th>Исполнитель</th>
                    <th>Приоритет</th>
                    <th>Дедлайн</th>
                    <th>Статус</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($tasks as $task)
                    <tr>
                        <td><a href="{{ route('tasks.show', $task) }}">{{ $task->title }}</a></td>
                        <td>{{ $task->project->name }}</td>
                        <td>{{ $task->assignee?->name ?? '—' }}</td>
                        <td><span class="badge {{ $task->priorityBadge() }}">{{ $task->priorityLabel() }}</span></td>
                        <td>{{ $task->deadline?->format('d.m.Y') ?? '—' }}</td>
                        <td><span class="badge {{ $task->statusBadge() }}">{{ $task->statusLabel() }}</span></td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="text-center text-muted py-3">Задачи не найдены.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="mt-3">
    {{ $tasks->links() }}
</div>
@endsection
