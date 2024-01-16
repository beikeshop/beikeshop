@extends('layout.master')
@section('body-class', 'page-categories')

@section('content')
  <x-shop-breadcrumb type="static" value="products.search" />

  <div class="container">
    <div class="row">
      @if (count($items))
        @foreach ($items as $product)
          <div class="col-6 col-md-3">@include('shared.product')</div>
        @endforeach
      @else
        <x-shop-no-data />
      @endif
    </div>

    {{ $products->links('shared/pagination/bootstrap-4') }}
  </div>

@endsection
