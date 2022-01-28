@extends('layouts.app')

@section('title')
    {{ $title }}
@endsection

@section('content')
    <table class="table table-bordered">
        <thead>
            <h1>Список зарегистрированых пользователей</h1>
            <th>#</th>
            <th>Nickname</th>
            <th>E-mail</th>
            <th>Вход под пользователем</th>
        </thead>
        <tbody>

            @foreach ($users as $key => $user)
                <tr>
                    <td>{{ $key }}</td>
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
@endsection
