@extends('layouts.app')

@section('title')
    {{ $title }}
@endsection

@section('content')

    <div class = 'container'>
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        

        @if (session()->has('category_updated'))
        <div class="alert alert-info text-center">
                Профиль успешно обновлен
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
            </div>        
    </div>
@endsection    
