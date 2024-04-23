@extends('layout.master')
@section('body-class', 'page-account-order-info')
@section('title',  __('shop/checkout.checkout_success_title'))

@section('content')

<div class="container">
  <div class="card mt-lg-5 mt-3 w-max-1000 mx-auto">
    <div class="card-body p-0">
      <div class="text-center">
        <div class="text-success mb-3">
          <i class="bi bi-check-circle" style="font-size: 60px"></i>
        </div>
        <div class="checkout-success__header__title">
          <h1>{{ __('shop/checkout.checkout_success_title') }}</h1>
          @hook('checkout.success.checkout_success_title.after')
        </div>
      </div>
      <div class="checkout-success__body">
        <div class="mt-4">
          <div class="card mb-2 order-head">
            <div class="card-header d-flex align-items-center justify-content-between">
              <h5 class="card-title">{{ __('shop/account/order_info.order_details') }}</h5>
              @if (current_customer())
                <a class="btn btn-sm btn-primary" href="{{ shop_route('account.order.show', $order->number) }}">{{ __('common.view_more') }}</a>
                @else
                <a class="btn btn-sm btn-primary" href="{{ shop_route('orders.show', ['number' => $order->number, 'email' => $order->email]) }}">{{ __('common.view_more') }}</a>
              @endif
            </div>
            <div class="card-body">
              <div class="bg-light p-2 table-responsive">
                @if (!is_mobile())
                  <table class="table table-borderless mb-0">
                    <thead>
                      <tr>
                        <th>{{ __('shop/account/order_info.order_number') }}</th>
                        <th class="nowrap">{{ __('shop/account/order_info.order_date') }}</th>
                        <th>{{ __('shop/account/order_info.state') }}</th>
                        <th>{{ __('shop/account/order_info.order_amount') }}</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr>
                        <td>{{ $order->number }}</td>
                        <td>{{ $order->created_at }}</td>
                        <td>
                          {{ $order->status_format }}
                        </td>
                        <td>{{ currency_format($order->total, $order->currency_code, $order->currency_value) }}</td>
                      </tr>
                    </tbody>
                  </table>
                @else
                  <div class="d-flex justify-content-between mb-2">
                    <div>{{ __('shop/account/order_info.order_number') }}</div>
                    <div class="fw-bold">{{ $order->number }}</div>
                  </div>
                  <div class="d-flex justify-content-between mb-2">
                    <div>{{ __('shop/account/order_info.order_date') }}</div>
                    <div class="fw-bold">{{ $order->created_at }}</div>
                  </div>
                  <div class="d-flex justify-content-between mb-2">
                    <div>{{ __('shop/account/order_info.state') }}</div>
                    <div class="fw-bold">{{ $order->status_format }}</div>
                  </div>
                  <div class="d-flex justify-content-between">
                    <div>{{ __('shop/account/order_info.order_amount') }}</div>
                    <div class="fw-bold">{{ currency_format($order->total, $order->currency_code, $order->currency_value) }}</div>
                  </div>
                @endif
              </div>
            </div>
          </div>
          @hookwrapper('checkout.success.address_info')
          <div class="card">
            <div class="card-header"><h5 class="card-title">{{ __('order.address_info') }}</h5></div>
            <div class="card-body">
              <table class="table">
                <thead class="">
                  <tr>
                    <th>{{ __('order.shipping_address') }}</th>
                    <th>{{ __('order.payment_address') }}</th>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <td>
                      <div>{{ __('address.name') }}：{{ $order->shipping_customer_name }} ({{ $order->shipping_telephone }})</div>
                      <div>
                        {{ __('address.address') }}：
                        {{ $order->shipping_address_1 }}
                        {{ $order->shipping_address_2 }}
                        {{ $order->shipping_city }}
                        {{ $order->shipping_zone }}
                        {{ $order->shipping_country }}
                      </div>
                      <div>{{ __('address.post_code') }}：{{ $order->shipping_zipcode }}</div>
                    </td>
                    <td>
                      <div>{{ __('address.name') }}：{{ $order->payment_customer_name }} ({{ $order->payment_telephone }})</div>
                      <div>
                        {{ __('address.address') }}：
                        {{ $order->payment_address_1 }}
                        {{ $order->payment_address_2 }}
                        {{ $order->payment_city }}
                        {{ $order->payment_zone }}
                        {{ $order->payment_country }}
                      </div>
                      <div>{{ __('address.post_code') }}：{{ $order->payment_zipcode }}</div>
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
          @endhookwrapper
        </div>
      </div>

      @hook('checkout.success.footer')
    </div>
  </div>
</div>
@endsection
