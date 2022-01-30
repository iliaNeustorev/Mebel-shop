@extends('layouts.app')

@section('title')
    {{ $category->name }}
@endsection

@section('content')
    <div class="container">
        <h2>Категория: {{ $category->name }}</h2>
        <h4 class="card-text">{{ $category->description }}</h4>

        <div class="row">
            <category-component :category-id="{{$category->id}}"></category-component>
        </div>
    </div>

@endsection
