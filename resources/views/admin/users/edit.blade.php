@extends('layouts.app')

@section('title', 'Роль пользователя')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-6">
        <h3 class="mb-3">Роль пользователя</h3>

        <div class="card shadow-sm">
            <div class="card-body">
                <p><strong>{{ $user->name }}</strong> ({{ $user->email }})</p>

                <form method="POST" action="{{ route('admin.users.update', $user) }}">
                    @csrf
                    @method('PATCH')

                    <div class="mb-3">
                        <label class="form-label">Роль</label>
                        <select name="role" class="form-select @error('role') is-invalid @enderror">
                            <option value="admin" @selected($user->role==='admin')>Администратор</option>
                            <option value="manager" @selected($user->role==='manager')>Менеджер</option>
                            <option value="executor" @selected($user->role==='executor')>Исполнитель</option>
                        </select>
                        @error('role')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <button type="submit" class="btn btn-primary">Сохранить</button>
                    <a href="{{ route('admin.users.index') }}" class="btn btn-link">Назад</a>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
