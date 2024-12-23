@extends('layout.master')
@section('body-class', 'page-categories')
@section('title', $brand->name)

@section('content')
<x-shop-breadcrumb type="brand" :value="$brand" :is-full="true" />
<div class="container-fluid">
  @if (count($products_format))
  <div class="row g-3 g-lg-4">
    @foreach ($products_format as $product)
    <div class="col-6 col-md-3">@include('shared.product')</div>
    @endforeach
  </div>
  @else
  <x-shop-no-data />
  @endif

  {{ $products->links('shared/pagination/bootstrap-4') }}
</div>
@endsection