@extends('layouts.app')

@section('title', $task->title)

@section('content')
<div class="d-flex justify-content-between align-items-start mb-3">
    <div>
        <h3 class="mb-1">{{ $task->title }}</h3>
        <small class="text-muted">
            Проект: <a href="{{ route('projects.show', $task->project) }}">{{ $task->project->name }}</a>
        </small>
    </div>
    <div>
        @can('update', $task)
            <a href="{{ route('tasks.edit', $task) }}" class="btn btn-outline-primary">Изменить</a>
        @endcan
        @can('delete', $task)
            <button type="button" class="btn btn-outline-danger" data-bs-toggle="modal" data-bs-target="#deleteTaskModal">
                Удалить
            </button>
        @endcan
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card shadow-sm mb-4">
            <div class="card-body">
                <p>{{ $task->description ?: 'Описание отсутствует.' }}</p>
                <hr>
                <div class="row small text-muted">
                    <div class="col-6 mb-2">Постановщик: <strong>{{ $task->creator->name }}</strong></div>
                    <div class="col-6 mb-2">Исполнитель: <strong>{{ $task->assignee?->name ?? '—' }}</strong></div>
                    <div class="col-6">Приоритет:
                        <span class="badge {{ $task->priorityBadge() }}">{{ $task->priorityLabel() }}</span>
                    </div>
                    <div class="col-6">Дедлайн: <strong>{{ $task->deadline?->format('d.m.Y') ?? '—' }}</strong></div>
                </div>
            </div>
        </div>

        {{-- Комментарии --}}
        <h5>Комментарии</h5>
        <div class="card shadow-sm mb-3">
            <div class="card-body">
                @forelse ($task->comments as $comment)
                    <div class="d-flex justify-content-between border-bottom py-2">
                        <div>
                            <strong>{{ $comment->author->name }}</strong>
                            <small class="text-muted">{{ $comment->created_at->format('d.m.Y H:i') }}</small>
                            <div>{{ $comment->body }}</div>
                        </div>
                        @can('delete', $comment)
                            <form method="POST" action="{{ route('comments.destroy', $comment) }}">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-link text-danger">Удалить</button>
                            </form>
                        @endcan
                    </div>
                @empty
                    <p class="text-muted mb-0">Комментариев пока нет.</p>
                @endforelse
            </div>
        </div>

        @can('comment', $task)
            <form method="POST" action="{{ route('comments.store', $task) }}">
                @csrf
                <div class="mb-2">
                    <textarea name="body" rows="2"
                              class="form-control @error('body') is-invalid @enderror"
                              placeholder="Написать комментарий...">{{ old('body') }}</textarea>
                    @error('body')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <button class="btn btn-primary btn-sm">Добавить комментарий</button>
            </form>
        @endcan
    </div>

    {{-- Боковая панель: быстрая смена статуса --}}
    <div class="col-md-4">
        <div class="card shadow-sm">
            <div class="card-header">Статус</div>
            <div class="card-body">
                <p>
                    <span class="badge {{ $task->statusBadge() }} fs-6">{{ $task->statusLabel() }}</span>
                </p>
                @can('updateStatus', $task)
                    <form method="POST" action="{{ route('tasks.status', $task) }}">
                        @csrf
                        @method('PATCH')
                        <select name="status" class="form-select mb-2">
                            <option value="todo" @selected($task->status==='todo')>К выполнению</option>
                            <option value="in_progress" @selected($task->status==='in_progress')>В работе</option>
                            <option value="done" @selected($task->status==='done')>Выполнено</option>
                        </select>
                        <button class="btn btn-success btn-sm w-100">Обновить статус</button>
                    </form>
                @endcan
            </div>
        </div>
    </div>
</div>

{{-- Модалка удаления задачи --}}
@can('delete', $task)
<div class="modal fade" id="deleteTaskModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Удалить задачу?</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">Задача «{{ $task->title }}» будет удалена безвозвратно.</div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Отмена</button>
                <form method="POST" action="{{ route('tasks.destroy', $task) }}">
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
