@extends('layouts.app')

@section('title')
    {{ $category->name }}
@endsection

@section('style')
    <style>

        .picture-product {
            height: 30%;
            width: 30%;
            border-radius: 10px;
        }

    </style>
@endsection

@section('content')

    <div class = 'container'>
        
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
        
        @if (session()->has('status_product'))
        <div class="alert alert-info text-center">
                {{ session('status_product') }}
            </div>
        @endif

        <form enctype="multipart/form-data" action="{{ route('upd_product') }}" method="post">
            @csrf
            <input hidden name="id" value="{{ $id }}">
            <div class="mb-3">
                <label class="form-label">
                    Имя продукта
                </label>
                <input required class="form-control w-50" name ='name' placeholder="Введите имя продукта" value="{{ $name }}">
            </div>
           
            <div class="mb-3">    
                <label class="form-label">
                    Описание продукта
                </label>
                <textarea required class="form-control w-50" name ='description' row = '3' placeholder="Введите описание продукта">{{ $description }}</textarea>
            </div>

            <div class="mb-3">    
                <label class="form-label">
                    Цена продукта
                </label>
                <input required class="form-control w-50" name ='price' row = '3' placeholder="Введите цену" value="{{ $price }}">
            </div>

            <div class="mb-3">    
                <label class="form-label">
                    Категория
                </label>
               
                <select class="form-control w-50" name="category">
                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                    @foreach ($categories as $categor)
                        <option value="{{ $categor->id }}">{{ $categor->name }}</option>
                    @endforeach 
                </select>
            
            </div>
            <div class="mb-3">
                @if($picture != null)
                    <img class="picture-product" src="{{ asset('storage/img/products') }}/{{ $picture }}">
                    <caption><- Текущая картинка</caption>
                @endif
            </div>
            <div class="mb-3">    
                <label class="form-label">
                    Выберите новую картинку для продукта
                </label>
                <br>
                <input type="file" name="picture">
            </div>
            <button type="submit" class='btn btn-success w-100 mb-3'>{{ $button }}</button>
        </form>
        @if($category->products->count() != 0)
       <form action="{{ route('delete_product') }}" method="post">
            @csrf
            <table class="table table-bordered text-center">
                <thead>
                <h4>Категория "<strong><em>{{ $category->name }}</em></strong> "</h4>
                <th></th>
                <th>Имя</th>
                <th>Описание</th>
                <th>Цена</th>
                <th>Изображение</th>
                <th>Дата создания</th>
                <th>Последнее изменение</th>
            </thead>
            <tbody>
                    @foreach ($category->products as $product) 
                        <tr>
                            <td>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="check_delete[]" value="{{ $product->id }}" id="flexCheckDefault">
                                    <label class="form-check-label" for="flexCheckDefault"></label>
                                </div>
                            </td>
                            <td><a href="{{ route('admin_get_product',  [$product->category->id, $product->id]) }}" title="Редактировать продукт">{{ $product->name }}</a></td>
                            <td>{{ $product->description }}</td>
                            <td>{{ $product->price }}</td>
                            <td>
                                <img class="avatar" src="{{ asset('storage/img/products') }}/{{ $product->picture }}" alt="{{ $product->name }}">
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
             
            </tbody>
            </table>
        </form>   
        @else
            <em>Продукты остсутвуют</em> 
        @endif        
    </div>
@endsection    