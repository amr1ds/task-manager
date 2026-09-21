@php($task = $task ?? null)
@php($selectedProject = old('project_id', $task->project_id ?? request('project_id')))

<div class="mb-3">
    <label class="form-label">Проект</label>
    <select name="project_id" class="form-select @error('project_id') is-invalid @enderror" required>
        <option value="">— выберите проект —</option>
        @foreach ($projects as $project)
            <option value="{{ $project->id }}" @selected($selectedProject == $project->id)>
                {{ $project->name }}
            </option>
        @endforeach
    </select>
    @error('project_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
</div>

<div class="mb-3">
    <label class="form-label">Название</label>
    <input type="text" name="title"
           class="form-control @error('title') is-invalid @enderror"
           value="{{ old('title', $task->title ?? '') }}" required>
    @error('title')<div class="invalid-feedback">{{ $message }}</div>@enderror
</div>

<div class="mb-3">
    <label class="form-label">Описание</label>
    <textarea name="description" rows="3"
              class="form-control @error('description') is-invalid @enderror">{{ old('description', $task->description ?? '') }}</textarea>
    @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
</div>

<div class="row">
    <div class="col-md-4 mb-3">
        <label class="form-label">Статус</label>
        <select name="status" class="form-select">
            @foreach (['todo' => 'К выполнению', 'in_progress' => 'В работе', 'done' => 'Выполнено'] as $value => $label)
                <option value="{{ $value }}" @selected(old('status', $task->status ?? 'todo') === $value)>{{ $label }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-4 mb-3">
        <label class="form-label">Приоритет</label>
        <select name="priority" class="form-select">
            @foreach (['low' => 'Низкий', 'medium' => 'Средний', 'high' => 'Высокий'] as $value => $label)
                <option value="{{ $value }}" @selected(old('priority', $task->priority ?? 'medium') === $value)>{{ $label }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-4 mb-3">
        <label class="form-label">Дедлайн</label>
        <input type="date" name="deadline"
               class="form-control @error('deadline') is-invalid @enderror"
               value="{{ old('deadline', isset($task) && $task->deadline ? $task->deadline->format('Y-m-d') : '') }}">
        @error('deadline')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
</div>

<div class="mb-3">
    <label class="form-label">Исполнитель</label>
    <select name="assignee_id" class="form-select">
        <option value="">— не назначен —</option>
        @foreach ($users as $user)
            <option value="{{ $user->id }}" @selected(old('assignee_id', $task->assignee_id ?? '') == $user->id)>
                {{ $user->name }} ({{ $user->role }})
            </option>
        @endforeach
    </select>
</div>
