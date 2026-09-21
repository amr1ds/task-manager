@extends('layouts.app')

@section('title', 'Новая задача')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <h3 class="mb-3">Новая задача</h3>

        <div class="card shadow-sm">
            <div class="card-body">
                <form method="POST" action="{{ route('tasks.store') }}">
                    @csrf
                    @include('tasks._form')

                    <button type="submit" class="btn btn-primary">Создать</button>
                    <a href="{{ route('tasks.index') }}" class="btn btn-link">Отмена</a>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
