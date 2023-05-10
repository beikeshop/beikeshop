@extends('layout.master')
@section('body-class', 'page-checkout-success')
@section('title',  __('shop/checkout.checkout_success_title'))

@section('content')

<div class="container">
  <div class="card mt-5 w-max-1000 mx-auto">
    <div class="card-body">
      <div class="text-center">
        <div class="text-success mb-3">
          <i class="bi bi-check-circle" style="font-size: 50px"></i>
        </div>
        <div class="checkout-success__header__title">
          <h1>{{ __('shop/checkout.checkout_success_title') }}</h1>
        </div>
      </div>
      <div class="checkout-success__body">
        <div class="mt-3">
          <div class="card mb-4 order-head">
            <div class="card-header d-flex align-items-center justify-content-between">
              <h6 class="card-title">{{ __('shop/account.order.order_info.order_details') }}</h6>
            </div>
            <div class="card-body">
              <div class="bg-light p-2 table-responsive">
                <table class="table table-borderless mb-0">
                  <thead>
                    <tr>
                      <th>{{ __('shop/account.order.order_info.order_number') }}</th>
                      <th class="nowrap">{{ __('shop/account.order.order_info.order_date') }}</th>
                      <th>{{ __('shop/account.order.order_info.state') }}</th>
                      <th>{{ __('shop/account.order.order_info.order_amount') }}</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr>
                      <td>{{ $order->number }}</td>
                      <td>{{ $order->created_at }}</td>
                      <td>
                        {{ __("common.order.{$order->status}") }}
                      </td>
                      <td>{{ currency_format($order->total, $order->currency_code, $order->currency_value) }}</td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<style>
  body {
    background-color: #f5f5f5;
  }
</style>

@endsection