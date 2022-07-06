@extends('layout.master')

@section('body-class', 'page-account-order-info')

@section('content')
  <div class="container">
    <nav aria-label="breadcrumb">
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="#">Home</a></li>
        <li class="breadcrumb-item active" aria-current="page">Library</li>
      </ol>
    </nav>

    <div class="row">

      <x-shop-sidebar/>
      {{-- {{ dd($order) }} --}}
      <div class="col-12 col-md-9">
        <div class="card mb-4 order-head">
          <div class="card-header"><h6 class="card-title">订单详情</h6></div>
          <div class="card-body">
            <div class="bg-light p-2">
              <table class="table table-borderless mb-0">
                <thead>
                  <tr>
                    <th>订单号</th>
                    <th>下单日期</th>
                    <th>状态</th>
                    <th>订单金额</th>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <td>{{ $order->number }}</td>
                    <td>{{ $order->created_at }}</td>
                    <td>{{ $order->status }}</td>
                    <td>{{ $order->total }}</td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>

        <div class="card mb-4">
          <div class="card-header"><h6 class="card-title">订购商品</h6></div>
          <div class="card-body">
            @foreach ($order->orderProducts as $product)
            <div class="product-list">
              <div class="left"><img src="{{ $product->image }}" class="img-fluid"></div>
              <div class="right">
                <div class="name">{{ $product->name }}  x {{ $product->quantity }}</div>
                <div class="price">{{ $product->price }}</div>
              </div>
            </div>
            @endforeach
          </div>
        </div>

        <div class="card mb-4">
          <div class="card-header"><h6 class="card-title">物流状态</h6></div>
          <div class="card-body">

          </div>
        </div>

        <div class="card mb-4">
          <div class="card-header"><h6 class="card-title">订单状态</h6></div>
          <div class="card-body">

          </div>
        </div>
      </div>
    </div>
  </div>
@endsection
