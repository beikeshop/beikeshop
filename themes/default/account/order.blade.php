@extends('layout.master')

@section('body-class', 'page-account-order-list')

@section('content')
  <x-shop-breadcrumb type="static" value="account.order.index" />

  <div class="container">
    <div class="row">
      <x-shop-sidebar />

      <div class="col-12 col-md-9">
        @if (!is_mobile())
        <div class="card mb-4 account-card order-wrap h-min-600">
          <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="card-title">{{ __('shop/account/order.index') }}</h5>
          </div>
          <div class="card-body">
            @include('account.order_status')
            <div class="table-responsive">
              <table class="table">
                @if (count($orders))
                  @foreach ($orders as $order)
                    <tbody>
                      <tr class="sep-row"><td colspan="4"></td></tr>
                      <tr class="head-tr">
                        <td colspan="4">
                          <span class="order-created me-4">{{ $order->created_at }}</span>
                          <span
                            class="order-number">{{ __('shop/account/order.order_number') }}：{{ $order->number }}</span>
                        </td>
                      </tr>
                      @foreach ($order->orderProducts as $product)
                        <tr class="{{ $loop->first ? 'first-tr' : '' }}">
                          <td>
                            <div class="product-info">
                              <div class="img border d-flex justify-content-center align-items-center wh-60"><img src="{{ image_resize($product->image) }}" class="img-fluid"></div>
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
                            <td rowspan="{{ $loop->count }}">{{$order->status_format}}</td>
                            <td rowspan="{{ $loop->count }}" class="text-end">
                              <a href="{{ shop_route('account.order.show', ['number' => $order->number]) }}" class="btn btn-outline-secondary btn-sm mb-2 w-100">{{ __('shop/account/order.check') }}</a>
                              @if ($order->status == 'unpaid')
                                <a href="{{ shop_route('orders.pay', $order->number) }}" class="btn w-100 btn-primary btn-sm nowrap mb-2">{{ __('shop/account/order_info.to_pay') }}</a>
                                <button class="btn btn-outline-danger btn-sm cancel-order w-100" data-number="{{ $order->number }}" type="button">{{ __('shop/account/order_info.cancel') }}</button>
                              @endif
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

            {{ $orders->links('shared/pagination/bootstrap-4') }}
          </div>
        </div>
        @else
        <div class="order-mb-wrap">
          @include('account.order_status')
          @if (count($orders))
            @foreach ($orders as $order)
            <div class="order-mb-list card mb-3">
              <div class="card-body">
                <div class="header-wrapper d-flex justify-content-between">
                  <div>{{ __('shop/account/order.order_number') }}：{{ $order->number }}</div>
                  <div>{{ $order->status_format }}</div>
                </div>
                <div class="content-wrapper">
                  <div class="order-product-wrap mb-2" onclick="window.location.href='{{ shop_route('account.order.show', ['number' => $order->number]) }}'">
                    @foreach ($order->orderProducts as $product)
                    <div class="product-info d-flex mb-2">
                      <div class="img border d-flex justify-content-center align-items-center wh-60"><img src="{{ $product->image }}" class="img-fluid"></div>
                      <div class="name ms-2">
                        <div>{{ $product->name }}</div>
                        <div class="quantity mt-1 text-secondary">x {{ $product->quantity }}</div>
                      </div>
                    </div>
                    @endforeach
                  </div>
                </div>
                <div class="footer-wrapper">
                  <div class="d-flex justify-content-between">
                    <div class="text-secondary">{{ $order->created_at }}</div>
                    <div class="fw-bold">{{ __('admin/order.total') }}：<span class="amount text-primary">{{ currency_format($order->total, $order->currency_code, $order->currency_value) }}</span></div>
                  </div>
                  <div class="footer-btns d-flex justify-content-end mt-2">
                    @if ($order->status == 'unpaid')
                      <a href="{{ shop_route('orders.pay', $order->number) }}" class="btn btn-primary btn-sm nowrap">{{ __('shop/account/order_info.to_pay') }}</a>
                      <button class="btn btn-outline-danger ms-2 btn-sm cancel-order" data-number="{{ $order->number }}" type="button">{{ __('shop/account/order_info.cancel') }}</button>
                    @endif
                    <a href="{{ shop_route('account.order.show', ['number' => $order->number]) }}"
                      class="btn btn-outline-secondary btn-sm ms-2">{{ __('shop/account/order.check') }}</a>
                  </div>
                </div>
              </div>
            </div>
            @endforeach

            {{ $orders->links('shared/pagination/bootstrap-4') }}
          @else
            <x-shop-no-data />
          @endif
        </div>
        @endif
      </div>
    </div>
  </div>
@endsection

@push('add-scripts')
<script>
  $('.cancel-order').click(function(event) {
    var number = $(this).data('number')
    $http.post(`orders/${number}/cancel`).then((res) => {
      layer.msg(res.message)
      window.location.reload()
    })
  });
</script>
@endpush