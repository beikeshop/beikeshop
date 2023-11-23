@extends('layout.master')

@section('body-class', 'page-account')

@section('content')
  <x-shop-breadcrumb type="static" value="account.index" />

  <div class="container">
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

        <div class="card account-card">
          <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="card-title">{{ __('shop/account.my_order') }}</h5>
            <a href="{{ shop_route('account.order.index') }}" class="text-muted">{{ __('shop/account.orders') }}</a>
          </div>
          <div class="card-body">
            <div class="d-flex flex-nowrap card-items mb-4 py-3">
              <a href="{{ shop_route('account.order.index', ['status' => 'unpaid']) }}" class="d-flex flex-column align-items-center"><i class="iconfont">&#xf12f;</i><span
                  class="text-muted text-center">{{ __('order.unpaid') }}</span></a>
              <a href="{{ shop_route('account.order.index', ['status' => 'paid']) }}" class="d-flex flex-column align-items-center"><i class="iconfont">&#xf130;</i><span
                  class="text-muted text-center">{{ __('shop/account.pending_send') }}</span></a>
              <a href="{{ shop_route('account.order.index', ['status' => 'shipped']) }}" class="d-flex flex-column align-items-center"><i class="iconfont">&#xf131;</i><span
                  class="text-muted text-center">{{ __('shop/account.pending_receipt') }}</span></a>
              <a href="{{ shop_route('account.rma.index') }}" class="d-flex flex-column align-items-center"><i class="iconfont">&#xf132;</i><span
                  class="text-muted text-center">{{ __('shop/account.after_sales') }}</span></a>
            </div>
            <div class="order-wrap">
              @if (!count($latest_orders))
                <div class="no-order d-flex flex-column align-items-center">
                  <div class="icon mb-2"><i class="iconfont">&#xe60b;</i></div>
                  <div class="text mb-3 text-muted">{{ __('shop/account.no_order') }}<a href="">{{ __('shop/account.to_buy') }}</a></div>
                </div>
              @else
                {{-- <p class="text-muted">近期订单</p> --}}
                <ul class="list-unstyled orders-list table-responsive">
                  <table class="table table-hover">
                    <tbody>
                      @foreach ($latest_orders as $order)
                      <tr class="align-middle">
                        <td style="width: 62px">
                          <div class="img border wh-60 d-flex justify-content-center align-items-center">
                            <img src="{{ $order->orderProducts[0]->image ?? '' }}" class="img-fluid">
                          </div>
                        </td>
                        <td>
                          <div class="mb-2 text-nowrap">{{ __('shop/account.order_number') }}：<span style="width: 110px;display: inline-block;">{{ $order->number }}</span> <span class="vr lh-1 me-2 bg-secondary"></span> {{ __('shop/account.all') }} {{ count($order->orderProducts) }} {{ __('shop/account.items') }}</div>
                          <div class="text-muted">{{ __('shop/account.order_time') }}：{{ $order->created_at }}</div>
                        </td>
                        <td>
                          <span class="ms-4 text-nowrap d-inline-block">{{ __('shop/account.state') }}：{{ $order->status_format }}</span>
                        </td>
                        <td>
                          <span class="ms-3 text-nowrap d-inline-block">{{ __('shop/account.amount') }}：{{ currency_format($order->total, $order->currency_code, $order->currency_value) }}</span>
                        </td>

                        <td>
                          <a href="{{ shop_route('account.order.show', ['number' => $order->number]) }}"
                            class="btn btn-outline-secondary text-nowrap btn-sm">{{ __('shop/account.check_details') }}</a>
                        </td>
                      </tr>
                      @endforeach
                    </tbody>
                  </table>
                </ul>
              @endif
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection
