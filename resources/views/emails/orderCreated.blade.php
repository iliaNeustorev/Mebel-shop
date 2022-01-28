<h1>Уважаемый {{ $data['user']['name'] }}</h1>

<table class="table table-bordered">
    <thead>
        <tr>
            <th>Название</th>
            <th>Цена</th>
            <th>Количество</th>
            <th>Сумма</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($data['products'] as $product)
        <tr>
            <td>{{ $product['name'] }}</td>
            <td>{{ $product['price'] }}</td>
            <td>{{ $product['quantity'] }}</td>
            <td>{{ $product['quantity'] * $product['price'] }}</td>
        </tr>
        @endforeach
        <tr>
            <td colspan="4">
                <span>Общая сумма заказа: {{ $data['sum_order'] }}</span>
            </td>
        </tr>
    </tbody>
   
</table>