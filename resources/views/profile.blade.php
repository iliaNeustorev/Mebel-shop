@extends('layouts.app')

@section('title')
    {{ $title }} {{ $user->name }}
@endsection

@section('content')

    <profile-component :error-list='{{ $errors }}' :user="{{ $user }}" :addresses='{{ $user->addresses }}'></profile-component>

@endsection
