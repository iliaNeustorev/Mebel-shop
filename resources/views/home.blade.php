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
<div class="container">
    @if (session()->has('another_user'))
        <div class="alert alert-info text-center">
            Вход под {{ $user->name }} выполнен успешно
        </div>
    @else
        @auth
            Здраствуйте {{ $user->name }}
        @endauth
    @endif
        @guest
            вы не авторизованы, авторизуйтесь
        @endguest

            @if ($show_title)
                <h1 class="mt-2 mb-3 text-center">Добро пожаловать в интернет-магазин  {{ config('app.name', 'Laravel') }}</h1>
            @endif

            <div class="row">

                @foreach ($categories as $category)
                <a href="{{ route('category', $category->id) }}" class="col-3 mb-4" title="Посмотреть товары категории {{ $category->name }}">
                    <div class="card" style="width: 18rem;">
                    <img style="width: 100%;height:300px" src="{{ asset('storage/img/categories/') }}/{{ $category->picture }}" class="card-img-top" alt="{{ $category->name }}">
                        <div class="card-body">
                            <h5 class="card-title text-center">{{ $category->name }} ({{ $category->products->count()}})</h5>
                        </div>
                    </div>
                </a>
                @endforeach           
            </div>
           
</div>
@endsection
