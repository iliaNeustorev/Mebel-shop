@extends('layouts.app')

@section('style')
    <style>

        .card-basket-buttons {
            display: flex;
            justify-content:space-between;
        }
        .card-counter {
        line-height: 34px;
        }

        .card-price {
            font-size: 130%;
            border-bottom: 2px solid gray;
        }

        .card-text {
            height: 70px;
        }

        .card-title {
            height: 45px;
            text-align: center;
            font-weight: bold;
        }
    </style>
@endsection

@section('title')
    {{ $category->name }}
@endsection

@section('content')
<div class="container"> 
    <h2>Категория: {{ $category->name }}</h2>
    <h4 class="card-text">{{ $category->description }}</h4>

        @if ($category->products->count() > 0)
        <div class="row"> 
            @foreach ($category->products as $product)
                <div class="col-3 mb-4">
                    <div class="card" style="width: 18rem;">
                        <img style="width: 100%;height:300px" src="{{ asset('storage/img/products/') }}/{{ $product->picture }}" class="card-img-top" alt="{{ $product->name }}">
                            <div class="card-body">
                                <h5 class="card-title"> {{ $product->name }}</h5>
                                <p class="card-text">{{ substr($product->description, 0, 90) }}...</p>
                                <div class="card-basket-buttons">
                                    <form method="POST" action="{{ route('addProduct') }}">
                                        @csrf
                                        <input type="hidden" name="id" value="{{ $product->id }}">
                                        <button type="submit" class="btn btn-danger">+</button>
                                    </form>
                                    <div class="card-counter">
                                        {{ session("products.{$product->id}") }}
                                    </div>
                                    <form method="POST" action="{{ route('removeProduct') }}">
                                        @csrf
                                        <input type="hidden" name="id" value="{{ $product->id }}">
                                        <button type="submit" class="btn btn-success">-</button>
                                    </form>
                                </div>
                                <p class="card-price mt-1 text-center">{{ $product->price }} руб.</p>  
                            </div>
                        </div>
                </div>
            @endforeach
        </div> 
        @else
            <em>Продуктов нет<em>  
        @endif
    </ul>     
</div>

@endsection