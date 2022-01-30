@extends('layouts.app')

@section('content')
    <div class="container">
        @foreach (Auth::user()->orders as $order)
            <p>
                <a class="btn btn-link" data-bs-toggle="collapse" href="#collapseExample{{ $order->id }}" role="button"
                    aria-expanded="false" aria-controls="collapseExample">
                    #{{ $order->id }}
                </a>
                {{ date('d.m.Y H:i:s', strtotime($order->created_at)) }}
            </p>
            <div class="collapse mb-4" id="collapseExample{{ $order->id }}">
                <div class="card card-body">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Наименование:</th>
                                <th>Количество:</th>
                                <th> Цена:</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($order->products as $product)
                                <tr>
                                    <td>{{ $product->name }}
                                    <td> {{ $product->pivot->quantity }}</td>
                                    <td>{{ $product->pivot->price }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <a href="{{ route('repeatOrder', $order->id) }}"><button class="btn btn-info w-50">Повторить заказ</button></a>
                </div>
            </div>
        @endforeach
    </div>
@endsection
