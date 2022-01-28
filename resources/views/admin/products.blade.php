@extends('layouts.app')

@section('title')
    {{ $title }}
@endsection

@section('style')
    <style>
        .avatar {
            height: 200px;
            width: 200px;
        }

    </style>
@endsection

@section('content')
    <div class='container'>
        <div class="d-grid gap-2 d-md-block text-center">
            <h2 class="text-center">Список продуктов</h2>
            <form action="{{ route('exportProducts') }}" method="post">
                @csrf
                <button type="submit" class="btn btn-link btn-sm">Выгрузить список продуктов</button>
            </form>
            <form action="{{ route('importProducts') }}" method="post">
                @csrf
                <button type="submit" class="btn btn-link btn-sm">Загрузить список продуктов</button>
            </form>
        </div>
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if (session()->has('startexportProducts'))
            <div class="alert alert-success text-center">
                Выгрузка списка продуктов запущена
            </div>
        @endif

        @if (session()->has('startimportProducts'))
            <div class="alert alert-success text-center">
                Загрузка списка продуктов запущена
            </div>
        @endif

        @if (session()->has('product_add'))
            <div class="alert alert-info text-center">
                Продукт успешно добавлен
            </div>
        @endif
        <form enctype="multipart/form-data" action="{{ route('add_product') }}" method="post">
            @csrf
            <div class="mb-3">
                <label class="form-label">
                    Имя продукта
                </label>
                <input required class="form-control w-50" name='name' placeholder="Введите имя продукта">
            </div>

            <div class="mb-3">
                <label class="form-label">
                    Описание продукта
                </label>
                <textarea required class="form-control w-50" name='description' row='3'
                    placeholder="Введите описание продукта"></textarea>
            </div>

            <div class="mb-3">
                <label class="form-label">
                    Цена продукта
                </label>
                <input required class="form-control w-50" name='price' row='3' placeholder="Введите цену">
            </div>

            <div class="mb-3">
                <label class="form-label">
                    Категория
                </label>
                <select class="form-control w-50" name="category">
                    <option selected disabled>---Выберите категорию---</option>
                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label">
                    Выберите картинку для продукта
                </label>
                <br>
                <input type="file" name="picture">
            </div>
            <button type="submit" class='btn btn-success w-100'>Добавить новый продукт</button>
        </form>
        <hr>
        <form method="post" action="{{ route('delete_product') }}">
            <table class="table table-bordered mt-2 text-center">
                <thead>
                    <th></th>
                    <th>Имя</th>
                    <th>Описание</th>
                    <th>Цена</th>
                    <th>Категория</th>
                    <th>Изображение</th>
                    <th>Дата создания</th>
                    <th>Последнее изменение</th>
                </thead>
                <tbody>

                    @foreach ($products as $product)
                        @csrf
                        <tr>
                            <td>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="check_delete[]"
                                        value="{{ $product->id }}" id="flexCheckDefault">
                                    <label class="form-check-label" for="flexCheckDefault"></label>
                                </div>

                            </td>
                            <td><a href="{{ route('admin_get_product', [$product->category->id, $product->id]) }}"
                                    title="Редактировать продукт">{{ $product->name }}</a></td>
                            <td>{{ $product->description }}</td>
                            <td>{{ $product->price }}</td>
                            <td><a href="{{ route('admin_get_product', $product->category->id) }}"
                                    title="Вывести список продуктов категории">{{ $product->category->name }}</a></td>
                            <td>
                                <a href="{{ route('admin_get_product', [$product->category->id, $product->id]) }}"
                                    title="Редактировать продукт"><img class="avatar"
                                        src="{{ asset('storage/img/products') }}/{{ $product->picture }}"
                                        alt="{{ $product->name }}"></a>
                            </td>
                            <td>{{ $product->created_at }}</td>
                            <td>{{ $product->updated_at }}</td>
                        </tr>
                    @endforeach
                    <tr>
                        <td class='text-center' colspan=8>
                            <button type="submit" class="btn btn-danger btn-sm">Удалить выбраные записи</button>
                        </td>
                    </tr>
                    <tr>
                        <td class='text-center' colspan=8>

                        </td>
                    </tr>
        </form>
        </tbody>
        </table>
    </div>
@endsection
