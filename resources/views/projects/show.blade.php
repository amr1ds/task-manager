@extends('layouts.app')

@section('title', $project->name)

@section('content')
<div class="d-flex justify-content-between align-items-start mb-3">
    <div>
        <h3 class="mb-1">{{ $project->name }}</h3>
        <small class="text-muted">Владелец: {{ $project->owner->name }}</small>
    </div>
    <div>
        @can('update', $project)
            <a href="{{ route('projects.edit', $project) }}" class="btn btn-outline-primary">Изменить</a>
        @endcan
        @can('delete', $project)
            <button type="button" class="btn btn-outline-danger" data-bs-toggle="modal" data-bs-target="#deleteProjectModal">
                Удалить
            </button>
        @endcan
    </div>
</div>

@if ($project->description)
    <div class="card shadow-sm mb-4">
        <div class="card-body">{{ $project->description }}</div>
    </div>
@endif

<div class="d-flex justify-content-between align-items-center mb-2">
    <h5 class="mb-0">Задачи проекта</h5>
    @can('create', App\Models\Task::class)
        <a href="{{ route('tasks.create', ['project_id' => $project->id]) }}" class="btn btn-sm btn-primary">
            <i class="bi bi-plus-lg"></i> Добавить задачу
        </a>
    @endcan
</div>

<div class="card shadow-sm">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th>Задача</th>
                    <th>Исполнитель</th>
                    <th>Приоритет</th>
                    <th>Статус</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($project->tasks as $task)
                    <tr>
                        <td><a href="{{ route('tasks.show', $task) }}">{{ $task->title }}</a></td>
                        <td>{{ $task->assignee?->name ?? '—' }}</td>
                        <td><span class="badge {{ $task->priorityBadge() }}">{{ $task->priorityLabel() }}</span></td>
                        <td><span class="badge {{ $task->statusBadge() }}">{{ $task->statusLabel() }}</span></td>
                    </tr>
                @empty
                    <tr><td colspan="4" class="text-center text-muted py-3">В проекте пока нет задач.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{-- Модальное окно подтверждения удаления (Bootstrap) --}}
@can('delete', $project)
<div class="modal fade" id="deleteProjectModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Удалить проект?</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                Проект «{{ $project->name }}» и все его задачи будут удалены безвозвратно.
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Отмена</button>
                <form method="POST" action="{{ route('projects.destroy', $project) }}">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Удалить</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endcan
@endsection
