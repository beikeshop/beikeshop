@extends('layout.master')
@section('body-class', 'page-categories')

@section('content')
  <div class="container">

    <x-shop-breadcrumb type="category" :value="$category" />

    <div class="row">
      @if (count($products))
        @foreach ($products as $product)
          <div class="col-6 col-md-3">@include('shared.product')</div>
        @endforeach
      @else
        <x-shop-no-data />
      @endif
    </div>
  </div>

@endsection
