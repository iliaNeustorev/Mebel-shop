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
@if (session('products') != null)
    <table class="table table-bordered">
        <thead>
            <tr>
                <td>Название</td>
                <td>Цена</td>
                <td>Количество</td>
                <td>Сумма</td>
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
@else
    <div class="text-center mb-5">
        <i>Товаров нет в корзине</i>
    </div>
@endif
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
</div>
@endsection