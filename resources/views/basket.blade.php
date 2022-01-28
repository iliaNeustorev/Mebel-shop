@extends('layouts.app')

@section('title')
    {{ $title }}
@endsection

@section('content')
    <basket-component :products='{{ $products }}' route-login='{{ route('login') }}'
        route-home='{{ route('home') }}' :sum-order='{{ $sum_order }}' :error-list='{{ $errors }}'>
    </basket-component>
@endsection
