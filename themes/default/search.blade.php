@extends('layout.master')
@section('body-class', 'page-categories')

@section('content')
  <div class="container">

    <x-shop-breadcrumb type="static" value="products.search" />

    <div class="row">
      @foreach ($items as $product)
        <div class="col-6 col-md-3">@include('shared.product')</div>
      @endforeach
    </div>

    {{ $products->links('shared/pagination/bootstrap-4') }}
  </div>

@endsection
