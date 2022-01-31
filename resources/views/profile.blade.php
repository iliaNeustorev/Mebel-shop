@extends('layouts.app')

@section('title')
    {{ $title }} {{ $user->name }}
@endsection

@section('content')

    <profile-component route-profile="{{ route('profile') }}" :error-list='{{ $errors }}' :user="{{ $user }}" :addresses='{{ $user->addresses }}'></profile-component>

@endsection
