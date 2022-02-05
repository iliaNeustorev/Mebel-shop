@extends('layouts.app')

@section('style')
    <style>
        .avatar {
            height: 200px;
            width: 200px;
        }

    </style>
@endsection

@section('title')
    {{ $title }}
@endsection

@section('content')

    <div class='container'>
        <categoriesadmin-component route-export-categories="{{ route("exportCategories") }}" route-import-categories="{{ route("importCategories") }}"></categoriesadmin-component>
        <h2 class="text-center">{{ $caption }}</h2>
        <hr>
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif


        @if (session()->has('startimportCategories'))
            <div class="alert alert-success text-center">
                Загрузка списка категорий запущена
            </div>
        @endif

        @if (session()->has('category_updated'))
            <div class="alert alert-info text-center">
                Категория успешно обновлена
            </div>
        @endif

        @if (session()->has('category_add'))
            <div class="alert alert-info text-center">
                Категория успешно добавлена
            </div>
        @endif

        @if (session()->has('error_updated_category'))
            <div class="alert alert-info text-center">
                Категория с таким именем уже существует
            </div>
        @endif

        @if (session()->has('del_category'))
            <div class="alert alert-info text-center">
                Категория успешно удалена
            </div>
        @endif

        <form enctype="multipart/form-data" action="{{ route('add_and_upd_category') }}" method="post">
            @csrf
            <input hidden name='id' value="{{ $id }}">
            <div class="mb-3">
                <label class="form-label">
                    Имя категории
                </label>
                <input required class="form-control w-50" name='name' placeholder="Введите имя категории"
                    value="{{ $name }}">
            </div>

            <div class="mb-3">
                <label class="form-label">
                    Описание категории
                </label>
                <textarea required class="form-control w-50" name='description' row='3'
                    placeholder="Введите описание категории">{{ $description }}</textarea>
            </div>

            <div class="mb-3">
                <label class="form-label">
                    Выберите картинку для категории
                </label>
                <br>
                <input type="file" name="picture">
            </div>
            <button type="submit" class='btn btn-success w-100'>{{ $button }}</button>
        </form>
        <hr>
        @if (isset($show_picture))
            <div class="text-center">
                <img class="picture" src="{{ asset('storage/img/categories') }}/{{ $show_picture }}">
                <br>
                <a href="{{ route('admin_categories') }}"><button
                        class="form-control btn btn-success mt-3 w-50">Назад</button></a>
            </div>
        @endif
        @if (isset($categories))
            <h2 class="text-center">Таблица со всеми категориями</h2>
            <table class="table table-bordered mt-2 text-center">
                <thead>
                    <th>Имя</th>
                    <th>Редактировать</th>
                    <th>Описание</th>
                    <th>Изображение</th>
                    <th>Кол-во товара</th>
                    <th>Дата создания</th>
                    <th>Последнее изменение</th>
                </thead>
                <tbody>
                    @foreach ($categories as $category)
                        <tr>
                            <td><a href="{{ route('admin_get_product', $category->id) }}"
                                    title="Вывести список продуктов">{{ $category->name }}</a></td>
                            <td>
                                <a href="{{ route('admin_category', $category->id) }}"
                                    title="Редактировать категорию"><button
                                        class="btn btn-info btn-sm mb-1">Edit</button></a>
                                @if ($category->products->count() == 0)
                                    <form action="{{ route('del_category') }}" method="post">
                                        @csrf
                                        <input type="hidden" name="id" value="{{ $category->id }}">
                                        <button class="btn btn-danger btn-sm">Delete</button>
                                    </form>
                                @endif
                            </td>
                            <td>{{ $category->description }}</td>
                            <td>
                                <img class="avatar"
                                    src="{{ asset('storage/img/categories') }}/{{ $category->picture }}">
                            </td>
                            <td>{{ $category->products->count() }}</td>
                            <td>{{ $category->created_at }}</td>
                            <td>{{ $category->updated_at }}</td>
                        </tr>
                        @endforeach
                        <tr>
                            <td class='text-center' colspan=7>
                                {{ $categories->links() }}
                            </td>
                        </tr>
                </tbody>
            </table>
        @endif
    </div>
@endsection
