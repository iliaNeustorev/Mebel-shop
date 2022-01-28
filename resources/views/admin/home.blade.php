@extends('layouts.app')

@section('title')
    {{ $title }}
@endsection

@section('content')

    <div class='container'>
        <div class="d-grid gap-2 d-md-block mb-3 text-center">
            <a href="{{ route('admin_categories') }}"><button class="btn btn-success btn-lg  mb-2">Посмотреть список
                    категорий</button></a>
            <br>
            <a href="{{ route('admin_products') }}"><button class="btn btn-warning btn-lg  mb-2">Посмотреть список
                    продуктов</button></a>
            <br>
            <a href="{{ route('showRegUsers') }}"><button class="btn btn-info btn-lg  mb-2">Посмотреть список
                    пользователей</button></a>
        </div>
    </div>
@endsection
