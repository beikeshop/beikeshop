@extends('layout.master')

@section('body-class', 'page-account')

@section('content')
  <div class="container">
    <nav aria-label="breadcrumb">
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="#">Home</a></li>
        <li class="breadcrumb-item active" aria-current="page">Library</li>
      </ol>
    </nav>

    <div class="row">

      <x-shop-sidebar />

      <div class="col-12 col-md-9">
        @if (\Session::has('success'))
          <div class="alert alert-success">
            <ul>
              <li>{!! \Session::get('success') !!}</li>
            </ul>
          </div>
        @endif
        @if (0)
        <div class="card mb-4 account-card">
          <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="card-title">个人中心</h5>
            <a href="{{ shop_route('account.edit.index') }}" class="text-muted">修改信息</a>
          </div>
          <div class="card-body">
            <div class="d-flex flex-nowrap card-items py-2">
              <a href="{{ shop_route('account.wishlist.index') }}" class="d-flex flex-column align-items-center"><i class="iconfont">&#xe77f;</i><span
                  class="text-muted">收藏</span></a>
              <a href="http://" class="d-flex flex-column align-items-center"><i class="iconfont">&#xe6a3;</i><span
                  class="text-muted">优惠券</span></a>
              <a href="http://" class="d-flex flex-column align-items-center"><i class="iconfont">&#xe6a3;</i><span
                  class="text-muted">优惠券</span></a>
            </div>
          </div>
        </div>
        @endif
        <div class="card account-card">
          <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="card-title">我的订单</h5>
            <a href="{{ shop_route('account.order.index') }}" class="text-muted">全部订单</a>
          </div>
          <div class="card-body">
            <div class="d-flex flex-nowrap card-items mb-4 py-3">
              <a href="{{ shop_route('account.order.index', ['status' => 'unpaid']) }}" class="d-flex flex-column align-items-center"><i class="iconfont">&#xf12f;</i><span
                  class="text-muted">待付款</span></a>
              <a href="{{ shop_route('account.order.index', ['status' => 'paid']) }}" class="d-flex flex-column align-items-center"><i class="iconfont">&#xf130;</i><span
                  class="text-muted">待发货</span></a>
              <a href="{{ shop_route('account.order.index', ['status' => 'shipped']) }}" class="d-flex flex-column align-items-center"><i class="iconfont">&#xf131;</i><span
                  class="text-muted">待收货</span></a>
              <a href="{{ shop_route('account.rma.index') }}" class="d-flex flex-column align-items-center"><i class="iconfont">&#xf132;</i><span
                  class="text-muted">售后</span></a>
            </div>
            <div class="order-wrap">
              @if (!$latest_orders)
                <div class="no-order d-flex flex-column align-items-center">
                  <div class="icon mb-2"><i class="iconfont">&#xe60b;</i></div>
                  <div class="text mb-3 text-muted">您还没有订单！<a href="">去下单</a></div>
                </div>
              @else
                {{-- <p class="text-muted">近期订单</p> --}}
                <ul class="list-unstyled orders-list table-responsive">
                  <table class="table table-hover">
                    <tbody>
                      @foreach ($latest_orders as $order)
                      <tr class="align-middle">
                        <td>
                          <div class="img me-3 border wh-60">
                            <img src="{{ $order->orderProducts[0]->image ?? '' }}" class="img-fluid">
                          </div>
                        </td>
                        <td>
                          <div class="mb-2">订单号：{{ $order->number }} <span class="vr lh-1 mx-2 bg-secondary"></span> 共 {{ count($order->orderProducts) }} 件商品</div>
                          <div class="text-muted">下单时间：{{ $order->created_at }}</div>
                        </td>
                        <td>
                          <span class="ms-4 d-inline-block">状态：{{ $order->status }}</span>
                        </td>
                        <td>
                          <span class="ms-3 d-inline-block">金额：{{ $order->total }}</span>
                        </td>

                        <td>
                          <a href="{{ shop_route('account.order.show', ['number' => $order->number]) }}"
                            class="btn btn-outline-secondary btn-sm">查看详情</a>
                        </td>
                      </tr>
                      @endforeach
                    </tbody>
                  </table>
                  {{-- @foreach ($latest_orders as $order)
                    <div class="d-flex justify-content-between align-items-center mb-3">
                      <div class="d-flex align-items-center">
                        <div class="img me-3 border wh-70">
                          @foreach ($order->orderProducts as $product)
                          <img src="{{ $product->image }}" class="img-fluid">
                          @endforeach
                        </div>
                        <div>
                          <div class="order-number mb-2">
                            <span class="wp-200 d-inline-block">订单号：{{ $order->number }}</span>
                            <span class="wp-200 ms-4 d-inline-block">状态：{{ $order->status }}</span>
                            <span class=" ms-3 d-inline-block">金额：{{ $order->total }}</span>
                          </div>
                          <div class="order-created text-muted">下单时间：{{ $order->created_at }}</div>
                        </div>
                      </div>

                      <a href="{{ shop_route('account.order.show', ['number' => $order->number]) }}"
                        class="btn btn-outline-secondary btn-sm">查看详情</a>
                    </div>
                  @endforeach --}}
                </ul>
              @endif
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection
