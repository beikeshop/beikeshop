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
    @foreach ($product->skus as $sku)
        <p>
            <span>model: {{ $sku->model }}</span>
            <span>price: {{ $sku->price }}</span>
            <a href="{{ route('shop.carts.store', $sku) }}">Add to cart</a>
        </p>
    @endforeach
</body>
</html>
