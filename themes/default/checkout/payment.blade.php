@extends('layout.master')

@section('body-class', 'page-payment')

@section('content')
  <x-shop-breadcrumb type="static" value="checkout.index" :text="[31231]" />

  <div class="container">
    @if (!is_mobile())
    <div class="row mt-5 mb-5 justify-content-center">
      <div class="col-12 col-md-9">@include('shared.steps', ['steps' => 3])</div>
    </div>
    @endif

    <div class="col-12">
      <div class="card order-wrap border">
        <div class="card-body main-body">
          <div class="order-top">
            <div class="left">
              <i class="bi bi-credit-card-2-back"></i>
            </div>
            <div class="right">
              <h3 class="order-title">{{ __('shop/checkout.payment_please_pay') }}</h3>
              <div class="order-info mb-lg-4 mb-2">
                <table class="table table-borderless">
                  <tbody>
                    <tr>
                      <td>{{ __('shop/checkout.payment_order_number') }}：<span class="fw-bold">{{ $order['number'] }}</span></td>
                      <td>{{ __('shop/checkout.payment_amounts_payable') }}：<span class="fw-bold">{{ $order['total_format'] }}</span></td>
                    </tr>
                    <tr>
                      <td>{{ __('shop/checkout.payment_payment_method') }}：<span class="fw-bold">{{ $order['payment_method_name'] }}</span></td>
                    </tr>
                  </tbody>
                </table>
              </div>

              {!! $payment !!}
            </div>
          </div>

          @hook('payment.footer')
        </div>
      </div>
    </div>
  </div>
@endsection
