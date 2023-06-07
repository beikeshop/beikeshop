@extends('layout.master')

@section('body-class', 'page-account-order-info')

@section('content')
  <div class="container">
    <div class="card mt-5 w-max-1000 mx-auto">
      @include('shared.order_info')
    </div>
  </div>
@endsection