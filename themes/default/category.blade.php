@extends('layout.master')
@section('body-class', 'page-categories')
@section('title', system_setting('base.meta_title', 'BeikeShop开源好用的跨境电商系统 - BeikeShop官网') .' - '. $category->description->name)

@section('content')
  <div class="container">

    <x-shop-breadcrumb type="category" :value="$category" />

    <div class="row">
      @if (count($products_format))
        @foreach ($products_format as $product)
          <div class="col-6 col-md-3">@include('shared.product')</div>
        @endforeach
      @else
        <x-shop-no-data />
      @endif
    </div>

    {{ $products->links('shared/pagination/bootstrap-4') }}

  </div>

@endsection
