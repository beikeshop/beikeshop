@extends('layout.master')

@section('body-class', 'page-account-order-info')

@section('content')
  <div class="container">

    <x-shop-breadcrumb type="order" value="{{ $order->number }}" />

    <div class="row">
      <x-shop-sidebar />

      <div class="col-12 col-md-9">
        <div class="card mb-4 order-head">
          <div class="card-header d-flex align-items-center justify-content-between">
            <h6 class="card-title">{{ __('shop/account.order.order_info.order_details') }}</h6>
            <div>
              @if ($order->status == 'unpaid')
                <a href="{{ shop_route('orders.pay', $order->number) }}" class="btn btn-primary btn-sm nowrap">{{ __('shop/account.order.order_info.to_pay') }}</a>
                <button class="btn btn-outline-secondary btn-sm cancel-order" type="button">{{ __('shop/account.order.order_info.cancel') }}</button>
              @endif
              @if ($order->status == 'shipped')
                <button class="btn btn-primary btn-sm shipped-ed" type="button">{{ __('shop/account.order.order_info.confirm_receipt') }}</button>
              @endif
            </div>
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

        <div class="card mb-4">
          <div class="card-header">
            <h6 class="card-title">{{ __('shop/account.order.order_info.order_items') }}</h6>
          </div>
          <div class="card-body">
            @foreach ($order->orderProducts as $product)
              <div class="product-list">
                <div class="d-flex">
                  <div class="left border d-flex justify-content-between align-items-center"><img src="{{ $product->image }}" class="img-fluid"></div>
                  <div class="right">
                    <div class="name">
                    <a class="text-dark" href="{{ shop_route('products.show', ['product' => $product->product_id]) }}">{{ $product->name }}</a>
                    </div>
                    <div class="price">
                      {{ currency_format($product->price, $order->currency_code, $order->currency_value) }}
                      x {{ $product->quantity }}
                      = {{ currency_format($product->price * $product->quantity, $order->currency_code, $order->currency_value) }}
                    </div>
                  </div>
                </div>
                @if ($order->status == 'completed')
                  <a href="{{ shop_route('account.rma.create', [$product->id]) }}" style="white-space: nowrap;"
                    class="btn btn-outline-primary btn-sm">{{ __('shop/account.order.order_info.apply_after_sales') }}</a>
                @endif
              </div>
            @endforeach
          </div>
        </div>

        <div class="card mb-4">
          <div class="card-header">
            <h6 class="card-title">{{ __('shop/account.order.order_info.order_total') }}</h6>
          </div>
          <div class="card-body">
            <table class="table table-bordered border">
              <tbody>
                @foreach (array_chunk($order->orderTotals->all(), 2) as $totals)
                  <tr>
                    @foreach ($totals as $total)
                      <td class="bg-light wp-200">{{ $total->title }}</td>
                      <td><strong>{{ currency_format($total->value, $order->currency_code, $order->currency_value) }}</strong></td>
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
              <h6 class="card-title">{{ __('shop/account.order.order_info.logistics_status') }}</h6>
            </div>
            <div class="card-body">

            </div>
          </div>
        @endif

        @if ($order->orderHistories->count())
          <div class="card mb-4">
            <div class="card-header">
              <h6 class="card-title">{{ __('shop/account.order.order_info.order_status') }}</h6>
            </div>
            <div class="card-body">
              <table class="table ">
                <thead class="">
                  <tr>
                    <th>{{ __('shop/account.order.order_info.state') }}</th>
                    <th>{{ __('shop/account.order.order_info.remark') }}</th>
                    <th>{{ __('shop/account.order.order_info.update_time') }}</th>
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
    $http.post('orders/{{ $order->number }}/complete').then((res) => {
      layer.msg(res.message)
      window.location.reload()
    })
  });

  $('.cancel-order').click(function(event) {
    $http.post('orders/{{ $order->number }}/cancel').then((res) => {
      layer.msg(res.message)
      window.location.reload()
    })
  });
</script>
@endpush
