@extends('layouts.app')

@section('title', 'Новый проект')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <h3 class="mb-3">Новый проект</h3>

        <div class="card shadow-sm">
            <div class="card-body">
                <form method="POST" action="{{ route('projects.store') }}">
                    @csrf
                    @include('projects._form')

                    <button type="submit" class="btn btn-primary">Создать</button>
                    <a href="{{ route('projects.index') }}" class="btn btn-link">Отмена</a>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
