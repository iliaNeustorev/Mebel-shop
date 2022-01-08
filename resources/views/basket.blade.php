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
</div>
@endsection