@extends('layouts.app')

@section('title', 'Дашборд')

@section('content')
<h3 class="mb-4">Дашборд</h3>

<div class="row g-3 mb-4">
    <div class="col-md-4">
        <div class="card text-bg-primary shadow-sm">
            <div class="card-body">
                <h6 class="card-title">Проектов</h6>
                <p class="display-6 mb-0">{{ $stats['projects'] }}</p>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card text-bg-info shadow-sm">
            <div class="card-body">
                <h6 class="card-title">Задач всего</h6>
                <p class="display-6 mb-0">{{ $stats['tasks'] }}</p>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card text-bg-success shadow-sm">
            <div class="card-body">
                <h6 class="card-title">Выполнено</h6>
                <p class="display-6 mb-0">{{ $stats['tasks_done'] }}</p>
            </div>
        </div>
    </div>
</div>

<div class="card shadow-sm">
    <div class="card-header">
        {{ auth()->user()->isExecutor() ? 'Мои задачи' : 'Последние задачи' }}
    </div>
    <div class="card-body p-0">
        @if ($myTasks->isEmpty())
            <p class="p-3 mb-0 text-muted">Задач пока нет.</p>
        @else
            <ul class="list-group list-group-flush">
                @foreach ($myTasks as $task)
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <div>
                            <a href="{{ route('tasks.show', $task) }}">{{ $task->title }}</a>
                            <small class="text-muted">— {{ $task->project->name }}</small>
                        </div>
                        <span class="badge {{ $task->statusBadge() }}">{{ $task->statusLabel() }}</span>
                    </li>
                @endforeach
            </ul>
        @endif
    </div>
</div>
@endsection
