@extends('layouts.app')

@section('title')
    {{ $title }}
@endsection

@section('content')
    <showusers-component :users="{{ $users }}"></showusers-component>
@endsection
