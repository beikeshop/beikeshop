@extends('layout.master')

@section('body-class', 'page-account-order-list')

@section('content')
  <div class="container">
    <nav aria-label="breadcrumb">
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="#">Home</a></li>
        <li class="breadcrumb-item active" aria-current="page">Library</li>
      </ol>
    </nav>
    {{-- {{ dd($orders) }} --}}


    <div class="row">
      <x-shop-sidebar/>

      <div class="col-12 col-md-9">
        <div class="card mb-4 account-card order-wrap">
          <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">我的订单</h5>
          </div>
          <div class="card-body">
            <table class="table ">
              <thead>
                <tr>
                  <th>订单详情</th>
                  <th>金额</th>
                  <th>状态</th>
                  <th class="text-end">操作</th>
                </tr>
              </thead>
              @foreach ($orders as $order)
                <tbody>
                  <tr class="sep-row"><td colspan="4"></td></tr>
                  <tr class="head-tr">
                    <td colspan="4">
                      <span class="order-created me-4">{{ $order->created_at }}</span>
                      <span class="order-number">订单号：{{ $order->number }}</span>
                    </td>
                  </tr>
                  @foreach ($order->orderProducts as $product)
                  <tr class="{{ $loop->first ? 'first-tr' : '' }}">
                    <td>
                      <div class="product-info">
                        <div class="img"><img src="{{ $product->image }}" class="img-fluid"></div>
                        <div class="name">
                          <span>{{ $product->name }}</span>
                        </div>
                        <div class="quantity">{{ $product->quantity }}</div>
                      </div>
                    </td>
                    @if ($loop->first)
                      <td rowspan="{{ $loop->count }}">{{ $order->total }}</td>
                      <td rowspan="{{ $loop->count }}">{{ $order->status }}</td>
                      <td rowspan="{{ $loop->count }}" class="text-end">
                        <a href="{{ shop_route('account.order.show', ['number' => $order->number]) }}" class="btn btn-outline-secondary btn-sm">查看</a>
                      </td>
                    @endif
                  </tr>
                  @endforeach
                </tbody>
              @endforeach
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection
