@extends('layout.master')

@section('body-class', 'page-bk-stripe')

@section('content')
  <div class="container">
    <div class="row mt-5 mb-5 justify-content-center">
      <div class="col-12 col-md-9">@include('shared.steps', ['steps' => 4])</div>
    </div>


    <div class="col-12">
      <div class="card order-wrap border total-wrap">
        <h5 class="checkout-title">订单结账</h5>
        <div class="card-body">
          <ul class="totals">
            <li><span>订单号</span><span>{{ $order->number }}</span></li>
            <li><span>应付总金额</span><span v-text="source.order.total">{{ $order->total }}</span></li>
          </ul>

          {!! $payment !!}
        </div>
      </div>
    </div>
  </div>
@endsection
