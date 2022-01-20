@extends('layouts.app')

@section('style')
    <style>

        span {
            float: right;
        }
    </style>
@endsection

@section('title')
   {{ $title }}
@endsection

@section('content')
<div class="container">
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
                @if ($errors->has('email'))
                   Ссылка на <a href="{{ route('login')}}">Вход</a>
                @endif
            </ul>
        </div>
    @endif

@if (session('products') != null)
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Название</th>
                <th>Цена</th>
                <th>Количество</th>
                <th>Сумма</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($products as $product)
            <tr>
                <td>{{ $product['title'] }}</td>
                <td>{{ $product['price'] }}</td>
                <td>{{ $product['quantity'] }}</td>
                <td>{{ $product['quantity'] * $product['price'] }}</td>
            </tr>
            @endforeach
            <tr>
                <td colspan="4">
                    <span>Общая сумма заказа: {{ $sum_order }}</span>
                </td>
            </tr>
        </tbody>
       
    </table>
    <form action="{{ route('create_order') }}" method="post">
        @csrf
        <label>Имя</label>
        <input name="name" class="form-control mb-2" value="{{ $name }}">
    
        <label>Адрес</label>
        <input  name="address" class="form-control mb-2" value="{{ $main_address->address ?? '' }}">
        
        <label>Почта</label>
        <input name="email" type="email" class="form-control mb-2" value="{{ $email }}">
        
        <button class="btn btn-success">Оформить заказ</button>
    </form>
@else
    <div class="text-center mb-5">
        <h3><i>Товаров нет в корзине</i></h3>
        <a href="{{ route('home') }}">Перейти в каталог</a>
    </div>

</div>
@endif
@endsection