@extends('layout.master')

@section('content')
    <div class="container">
        <h1>我的订单</h1>
        @foreach ($orders as $order)
            <div class="col-6 col-md-3">{{ $order->number }}</div>
        @endforeach
    </div>
@endsection
