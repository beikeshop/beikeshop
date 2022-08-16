@extends('layout.master')

@section('body-class', 'page-account-order-info')

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
        <div class="card mb-4 order-head">
          <div class="card-header d-flex align-items-center justify-content-between">
            <h6 class="card-title">订单详情</h6>
            <div>
              @if ($order->status == 'unpaid')
                <a href="{{ shop_route('orders.pay', $order->number) }}" class="btn btn-primary btn-sm nowrap">去支付</a>
              @endif
              @if ($order->status == 'shipped')
                <button class="btn btn-primary btn-sm shipped-ed" type="button">确认收货</button>
              @endif
            </div>
          </div>
          <div class="card-body">

            <div class="bg-light p-2 table-responsive">
              <table class="table table-borderless mb-0">
                <thead>
                  <tr>
                    <th>订单号</th>
                    <th class="nowrap">下单日期</th>
                    <th>状态</th>
                    <th>订单金额</th>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <td>{{ $order->number }}</td>
                    <td>{{ $order->created_at }}</td>
                    <td>
                      {{ $order->status }}
                    </td>
                    <td>{{ $order->total }}</td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>

        <div class="card mb-4">
          <div class="card-header">
            <h6 class="card-title">订购商品</h6>
          </div>
          <div class="card-body">
            @foreach ($order->orderProducts as $product)
              <div class="product-list">
                <div class="d-flex">
                  <div class="left"><img src="{{ $product->image }}" class="img-fluid"></div>
                  <div class="right">
                    <div class="name">{{ $product->name }} x {{ $product->quantity }}</div>
                    <div class="price">{{ $product->price }}</div>
                  </div>
                </div>
                @if ($order->status == 'completed')
                  <a href="{{ shop_route('account.rma.create', [$product->id]) }}"
                    class="btn btn-outline-primary btn-sm">申请售后</a>
                @endif
              </div>
            @endforeach
          </div>
        </div>

        <div class="card mb-4">
          <div class="card-header">
            <h6 class="card-title">Order Total</h6>
          </div>
          <div class="card-body">
            <table class="table table-bordered border">
              <tbody>
                @foreach (array_chunk($order->orderTotals->all(), 2) as $totals)
                  <tr>
                    @foreach ($totals as $total)
                      <td class="bg-light wp-200">{{ $total->title }}</td>
                      <td><strong>{{ $total->value }}</strong></td>
                    @endforeach
                  </tr>
                @endforeach
              </tbody>
            </table>
          </div>
        </div>

        @if (0)
          <div class="card mb-4">
            <div class="card-header">
              <h6 class="card-title">物流状态</h6>
            </div>
            <div class="card-body">

            </div>
          </div>
        @endif

        @if ($order->orderHistories->count())
          <div class="card mb-4">
            <div class="card-header">
              <h6 class="card-title">订单状态</h6>
            </div>
            <div class="card-body">
              <table class="table ">
                <thead class="">
                  <tr>
                    <th>状态</th>
                    <th>备注</th>
                    <th>更新时间</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach ($order->orderHistories as $orderHistory)
                    <tr>
                      <td>{{ $orderHistory->status }}</td>
                      <td><span class="fw-bold">{{ $orderHistory->comment }}</span></td>
                      <td><span class="fw-bold">{{ $orderHistory->created_at }}</span></td>
                    </tr>
                  @endforeach
                </tbody>
              </table>
            </div>
          </div>
        @endif
      </div>
    </div>
  </div>
@endsection


@push('add-scripts')
<script>
  $('.shipped-ed').click(function(event) {
    $http.post('orders/{{ $order->id }}/complete').then((res) => {
      layer.msg(res.message)
      window.location.reload()
    })
  });
</script>
@endpush