@extends('layout.master')

@section('body-class', 'page-order-success')

@section('content')
  <div class="container">
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
            <h3 class="order-title">恭喜您，订单生成成功！</h3>
            <div class="order-info">
              <table class="table table-borderless">
                <tbody>
                  <tr>
                    <td>订单编号：<span class="fw-bold">{{ $order['number'] }}</span></td>
                    <td>应付金额：<span class="fw-bold">{{ $order['total'] }}</span></td>
                  </tr>
                  <tr>
                    <td>支付方式：<span class="fw-bold">{{ $order['payment_method_name'] }}</span></td>
                    <td><a href="{{ shop_route('account.order.show', ['number' => $order->number]) }}">查看订单详情</a></td>
                  </tr>
                  <tr>
                    <td><a href="{{ shop_route('orders.pay', [$order['number']]) }}" class="btn btn-primary btn-sm">立即支付</a></td>
                  </tr>
                </tbody>
              </table>
            </div>

            <div class="text-muted mt-4">温馨提示：您的订单已生成成功，请尽快完成支付哦～</div>
            <div class="mt-3">您还可以：<a href="/">继续采购</a></div>
          </div>
        </div>
        <div class="order-bottom">
          <div class="text-muted">如果您在订单过程中有任何问题，可以随时联系我们客服人员：</div>
          <div>Emaill: {{ system_setting('base.email', '') }}</div>
          <div>服务热线: {{ system_setting('base.telephone', '') }}</div>
        </div>
      </div>
    </div>
  </div>
@endsection
@push('add-scripts')
  <script></script>
@endpush