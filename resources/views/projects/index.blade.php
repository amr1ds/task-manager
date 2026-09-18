@extends('layouts.app')

@section('title', 'Проекты')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h3 class="mb-0">Проекты</h3>
    @can('create', App\Models\Project::class)
        <a href="{{ route('projects.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-lg"></i> Новый проект
        </a>
    @endcan
</div>

<div class="card shadow-sm">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th>Название</th>
                    <th>Владелец</th>
                    <th class="text-center">Задач</th>
                    <th class="text-end">Действия</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($projects as $project)
                    <tr>
                        <td>
                            <a href="{{ route('projects.show', $project) }}">{{ $project->name }}</a>
                        </td>
                        <td>{{ $project->owner->name }}</td>
                        <td class="text-center">{{ $project->tasks_count }}</td>
                        <td class="text-end">
                            <a href="{{ route('projects.show', $project) }}" class="btn btn-sm btn-outline-secondary">Открыть</a>
                            @can('update', $project)
                                <a href="{{ route('projects.edit', $project) }}" class="btn btn-sm btn-outline-primary">Изменить</a>
                            @endcan
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="text-center text-muted py-3">Проектов пока нет.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="mt-3">
    {{ $projects->links() }}
</div>
@endsection
