@extends('layout.master')

@section('body-class', 'page-order-success')

@section('content')
  <div class="container">

    {{-- <x-shop-breadcrumb type="static" value="account.order.index" /> --}}

    <div class="row mt-5 justify-content-center mb-5">
      <div class="col-12 col-md-9">@include('shared.steps', ['steps' => 3])</div>
    </div>

    <div class="card order-wrap border">
      <div class="card-body main-body">
        <div class="order-top border-bottom">
          <div class="left">
            <i class="bi bi-check2-circle"></i>
          </div>
          <div class="right">
            <h3 class="order-title">{{ __('shop/account.order.order_success.order_success') }}</h3>
            <div class="order-info">
              <table class="table table-borderless">
                <tbody>
                  <tr>
                    <td>{{ __('shop/account.order.order_success.order_number') }}：<span class="fw-bold">{{ $order['number'] }}</span></td>
                    <td>{{ __('shop/account.order.order_success.amounts_payable') }}：<span class="fw-bold">{{ currency_format($order['total']) }}</span></td>
                  </tr>
                  <tr>
                    <td>{{ __('shop/account.order.order_success.payment_method') }}：<span class="fw-bold">{{ $order['payment_method_name'] }}</span></td>
                    <td><a href="{{ shop_route('account.order.show', ['number' => $order->number]) }}">{{ __('shop/account.order.order_success.view_order') }}</a></td>
                  </tr>
                  <tr>
                    <td><a href="{{ shop_route('orders.pay', [$order['number']]) }}" class="btn btn-primary btn-sm">{{ __('shop/account.order.order_success.pay_now') }}</a></td>
                  </tr>
                </tbody>
              </table>
            </div>

            <div class="text-muted mt-4">{{ __('shop/account.order.order_success.kind_tips') }}</div>
            <div class="mt-3">{{ __('shop/account.order.order_success.also') }}：<a href="/">{{ __('shop/account.order.order_success.continue_purchase') }}</a></div>
          </div>
        </div>
        <div class="order-bottom">
          <div class="text-muted">{{ __('shop/account.order.order_success.contact_customer_service') }}：</div>
          <div>{{ __('shop/account.order.order_success.emaill') }}: {{ system_setting('base.email', '') }}</div>
          <div>{{ __('shop/account.order.order_success.service_hotline') }}: {{ system_setting('base.telephone', '') }}</div>
        </div>
      </div>
    </div>
  </div>
@endsection
@push('add-scripts')
  <script></script>
@endpush
