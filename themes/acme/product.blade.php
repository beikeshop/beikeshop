<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
    <p>{{ $product->description->name }}</p>

    <h2>SKUs</h2>
    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>model</th>
                <th>sku</th>
                <th>price</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            @foreach ($product->skus as $sku)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $sku->model }}</td>
                    <td>{{ $sku->sku }}</td>
                    <td>{{ $sku->price }}</td>
                    <td><a href="{{ route('shop.carts.store', ['sku_id' => $sku->id]) }}">Add to cart</a></td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
