@extends('layout.master')

@section('content')
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
@endsection
