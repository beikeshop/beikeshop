@extends('layout.master')

@section('body-class', 'page-account-order-info')

@section('content')
  <x-shop-breadcrumb type="order" value="{{ $order->number }}" />

  <div class="container">
    <div class="row">
      <x-shop-sidebar />

      <div class="col-12 col-md-9">
        @include('shared.order_info')
      </div>
    </div>
  </div>
@endsection
