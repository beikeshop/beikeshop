@extends('layout.master')
@section('body-class', 'page-categories')

@section('content')
<div class="container">

    <x-shop-breadcrumb type="brand" :value="$brand" />

    <div class="row">
      @foreach ($products as $product)
      <div class="col-6 col-md-3">@include('shared.product')</div>
      @endforeach
    </div>
</div>

@endsection
