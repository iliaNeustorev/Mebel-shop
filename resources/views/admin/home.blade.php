@extends('layouts.app')

@section('title')
    {{ $title }}
@endsection

@section('content')

    <div class = 'container'>
        
        

        @if (session()->has('startexportCategories'))
        <div class="alert alert-success text-center">
                Выгрузка списка категорий запущена
            </div>
        @endif

            <table class="table table-bordered">
                <thead>
                    <h4>Список зарегистрированых пользователей</h4>
                </thead>
                <tbody>
                    @foreach ($users as $user)
                        <tr>
                            <td>{{ $user->id }}</td>
                            <td>{{ $user->name }}</td>
                            <td>{{ $user->email }}</td>
                            <td>
                                <a href="{{ route('enterAsUser', $user->id) }}">
                                    Войти
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody> 
            </table>
            <div class="d-grid gap-2 d-md-block">
                <a href="{{ route('admin_categories') }}"><button class = "btn btn-success btn-lg">Посмотреть список категорий</button></a>
                <a href="{{ route('admin_products') }}"><button class = "btn btn-success btn-lg">Посмотреть список продуктов</button></a>
                <form action="{{ route('exportCategories') }}" method="post">
                    @csrf
                    <button type="submit" class="btn btn-link btn-lg">Выгрузить список категорий</button>
                </form>
                <form action="{{ route('importCategories') }}" method="post">
                    @csrf
                    <button type="submit" class="btn btn-link btn-lg">Загрузить список категорий</button>
                </form>
            </div>        
    </div>
@endsection    
