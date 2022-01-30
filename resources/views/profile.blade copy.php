@extends('layouts.app')

@section('title')
    {{ $title }} {{ $user->name }}
@endsection

@section('content')

        <profile-component :error-list='{{ $errors }}' :user="{{ $user }}" route-profile="{{ route('profile_update') }}" route-del-address='{{ route('del_address') }}' :addresses='{{ $user->addresses }}'></profile-component>
        <div class="row justify-content">

            @if (session()->has('profileUpdated'))
                <div class="alert alert-info text-center">
                    Профиль успешно обновлен!
                </div>
            @endif

            @if (session()->has('currentPasswordError'))
                <div class="alert alert-danger text-center">
                    Неверный текущий пароль
                </div>
            @endif

            <form class="w-50" method="POST" action="{{ route('profile_update') }}"
                enctype="multipart/form-data">
                @csrf
                <div class="mb-3">
                    <label for="exampleInputEmail1" class="form-label">
                        Почта
                    </label>
                    <input type="email" name="email" class="form-control" value="{{ $user->email }}">
                </div>
                <div class="mb-3">
                    <label for="exampleInputPassword1" class="form-label">
                        Имя
                    </label>
                    <input type="text" class="form-control" name="name" value="{{ $user->name }}">
                </div>
                <div class="mb-3">
                    <div class="mb-3">
                        <label for="exampleInputPassword1" class="form-label">
                            Текущий пароль
                        </label>
                        <input type="password" class="form-control" name="current_password">
                    </div>
                    <div class="mb-3">
                        <label for="exampleInputPassword1" class="form-label">
                            Новый пароль
                        </label>
                        <input type="password" class="form-control" name="password">
                    </div>
                    <div class="mb-3">
                        <label for="exampleInputPassword1" class="form-label">
                            Повторите пароль
                        </label>
                        <input type="password" class="form-control" name="password_confirmation">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">
                            Изображение
                        </label>
                        <br>
                        <img class="avatar" src="{{ asset('storage/img/users/') }}/{{ $user->picture }}">
                        <input type="file" class="form-control" name="picture">
                    </div>

                    <div class="container addressess">
                        <label class="form-lable">
                            Список адресов
                        </label>
                        <br>
                        @foreach ($user->addresses as $address)
                            @if ($address->main)
                                <input id="{{ $address->id }}" type="radio" name="main_address"
                                    value="{{ $address->id }}" checked>

                                <label for="{{ $address->id }}">
                                    {{ $address->address }}
                                </label>
                                <form action="{{ route('del_address') }}" method="post">
                                    @csrf
                                    <input hidden name="address_id" value=" {{ $address->id }}">
                                </form>
                            @else
                                <input id="{{ $address->id }}" type="radio" name="main_address"
                                    value=" {{ $address->id }}">

                                <label for="{{ $address->id }}">
                                    {{ $address->address }}
                                </label>
                                <form action="{{ route('del_address') }}" method="post">
                                    @csrf
                                    <input hidden name="address_id" value=" {{ $address->id }}">
                                    <button title="Удалить адрес" type="submit"
                                        class="btn-del-address btn btn-danger btn-sm">X</button>
                                </form>
                            @endif
                            <br>
                        @endforeach
                        <div class="mb-3">
                            <label class="form-lable">
                                Новый адрес
                            </label>
                            <input name="new_address" class="form-control w-50">
                            <label>Сделать основным</label>
                            <input type="checkbox" name='main_new_address'>
                        </div>
                    </div>
                    <button class="btn btn-primary btn-lg mt-2">Сохранить</button>
            </form>
        </div>
    </div>

@endsection
