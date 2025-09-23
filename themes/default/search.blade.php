@extends('layout.master')
@section('body-class', 'page-categories')

@section('content')
  <x-shop-breadcrumb type="static" value="products.search" :is-full="true" />

  <div class="container-fluid">
    <div class="row g-3 g-lg-4">
      @if (count($items))
        @foreach ($items as $product)
          <div class="col-6 col-md-3 col-lg-20">@include('shared.product')</div>
        @endforeach
      @else
        <x-shop-no-data />
      @endif
    </div>

    {{ $products->links('shared/pagination/bootstrap-4') }}
  </div>

@endsection
