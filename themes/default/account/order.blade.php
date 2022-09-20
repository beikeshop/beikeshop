@extends('layout.master')

@section('body-class', 'page-account-order-list')

@section('content')
  <div class="container">

    <x-shop-breadcrumb type="static" value="account.order.index" />

    <div class="row">
      <x-shop-sidebar />

      <div class="col-12 col-md-9">
        <div class="card mb-4 account-card order-wrap">
          <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="card-title">{{ __('shop/account.order.index') }}</h5>
          </div>
          <div class="card-body">
            <div class="table-responsive">
              <table class="table ">
                <thead>
                  <tr>
                    <th>{{ __('shop/account.order.order_details') }}</th>
                    <th width="160px">{{ __('shop/account.order.amount') }}</th>
                    <th width="100px">{{ __('shop/account.order.state') }}</th>
                    <th width="100px" class="text-end">{{ __('common.action') }}</th>
                  </tr>
                </thead>
                @if (count($orders))
                  @foreach ($orders as $order)
                    <tbody>
                      <tr class="sep-row">
                        <td colspan="4"></td>
                      </tr>
                      <tr class="head-tr">
                        <td colspan="4">
                          <span class="order-created me-4">{{ $order->created_at }}</span>
                          <span
                            class="order-number">{{ __('shop/account.order.order_number') }}：{{ $order->number }}</span>
                        </td>
                      </tr>
                      @foreach ($order->orderProducts as $product)
                        <tr class="{{ $loop->first ? 'first-tr' : '' }}">
                          <td>
                            <div class="product-info">
                              <div class="img border d-flex justify-content-between align-items-center"><img src="{{ $product->image }}" class="img-fluid"></div>
                              <div class="name">
                                <a class="text-dark"
                                  href="{{ shop_route('products.show', ['product' => $product->product_id]) }}">{{ $product->name }}</a>
                                <div class="quantity mt-1 text-secondary">x {{ $product->quantity }}</div>
                              </div>
                            </div>
                          </td>
                          @if ($loop->first)
                            <td rowspan="{{ $loop->count }}">
                              {{ currency_format($order->total, $order->currency_code, $order->currency_value) }}</td>
                            <td rowspan="{{ $loop->count }}">{{ __("common.order.{$order->status}") }}</td>
                            <td rowspan="{{ $loop->count }}" class="text-end">
                              <a href="{{ shop_route('account.order.show', ['number' => $order->number]) }}"
                                class="btn btn-outline-secondary btn-sm">{{ __('shop/account.order.check') }}</a>
                            </td>
                          @endif
                        </tr>
                      @endforeach
                    </tbody>
                  @endforeach
                @else
                  <tbody>
                    <tr><td colspan="4" class="border-0"><x-shop-no-data /></td></tr>
                  </tbody>
                @endif
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection
