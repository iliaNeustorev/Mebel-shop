@extends('layouts.app')

@section('style')
    <style>
        a {
            text-decoration: none;
            color: rgb(17, 117, 25)
        }

        a:hover {
            color: rgb(153, 161, 29)
        }

        a:after {
            color: rgb(153, 161, 29)
        }

        .card:hover {
            box-shadow: 0.4em 0.4em 5px rgb(122 122 122 / 50%);
        }

    </style>
@endsection

@section('title')
    {{ $title }}
@endsection

@section('content')
    @if (session()->has('another_user'))
        <div class="alert alert-info text-center">
            Вход под {{ $user->name }} выполнен успешно
        </div>
    @else

    @endif
    <home-component :categories="{{ $categories }}"></home-component>
@endsection
