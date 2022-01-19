@extends('layouts.app')

@section('title')
    {{ $title }} {{ $user->name }}
@endsection

@section('style')
    <style>
        .btn-del-address {
            height: 20px;
            width: 20px;
        }
        .btn-sm {
            padding: 0.11rem 0.3rem;
            font-size: 0.587rem;
            border-radius: 0.2rem;
            }
            
        .form-check {
            display: block ruby;
            min-height: 1.44rem;
            padding-left: 1.5em;
            margin-bottom: 0.125rem;
        }

        .addressess {
            border: 2px dotted lavender;
            font-size: small;
        }
    </style>
@endsection

@section('content')
        <div class="container">
            <div class="row justify-content">
            
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                
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

                @if (session()->has('repeatAddressError'))
                    <div class="alert alert-danger text-center">
                      Такой адрес уже есть
                    </div>
                @endif

                    <form class="w-50" method="POST" action="{{ route('profile_update') }}" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-3">
                            <label for="exampleInputEmail1" class="form-label">
                                Почта
                            </label>
                            <input type="email" name="email" class="form-control" value = "{{ $user->email }}">
                            <div id="emailHelp" class="form-text">
                                We'll never share your email with anyone else.
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="exampleInputPassword1" class="form-label">
                                Имя
                            </label>
                            <input type="text" class="form-control" name="name"  value = "{{ $user->name }}">
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
                            <img class="avatar" src="{{ asset('storage/img/users/') }}/{{$user->picture}}">
                            <input type="file" class="form-control" name="picture">
                        </div>
                        
                        <div class="container addressess">
                            <label class="form-lable">
                            Список адресов
                            </label>
                            <br>  
                            @foreach ($user->addresses as $address)                         
                                @if ( $address->main )
                                    <input id="{{$address->id}}" type="radio" name="main_address" value="{{ $address->id }}" checked>
                                   
                                    <label for="{{$address->id}}">
                                        {{ $address->address }}
                                    </label>
                                    <form action="{{ route('del_address') }}" method="post">
                                        @csrf
                                        <input hidden name="address_id" value=" {{ $address->id }}">
                                    </form>
                                    @else
                                    <input id="{{$address->id}}" type="radio" name="main_address" value=" {{ $address->id }}">
                                    
                                    <label for="{{ $address->id }}">
                                        {{ $address->address }}   
                                    </label>
                                        <form action="{{ route('del_address') }}" method="post">
                                            @csrf
                                            <input hidden name="address_id" value=" {{ $address->id }}">
                                            <button title="Удалить адрес" type="submit" class="btn-del-address btn btn-danger btn-sm">X</button>
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
        </div>
    
@endsection
