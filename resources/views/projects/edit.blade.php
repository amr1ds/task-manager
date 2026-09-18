@extends('layouts.app')

@section('title', 'Редактирование проекта')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <h3 class="mb-3">Редактирование проекта</h3>

        <div class="card shadow-sm">
            <div class="card-body">
                <form method="POST" action="{{ route('projects.update', $project) }}">
                    @csrf
                    @method('PUT')
                    @include('projects._form')

                    <button type="submit" class="btn btn-primary">Сохранить</button>
                    <a href="{{ route('projects.show', $project) }}" class="btn btn-link">Отмена</a>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
