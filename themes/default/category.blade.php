@extends('layout.master')

@section('content')
  @foreach ($products as $product)
    <a href="{{ shop_route('products.show', $product) }}">
      <img src="{{ thumbnail($product->image) }}" alt="{{ $product->description->name }}">
      {{ $product->description->name }}
    </a>
  @endforeach

@endsection
