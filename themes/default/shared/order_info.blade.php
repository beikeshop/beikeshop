@if ($errors->any())
  @foreach ($errors->all() as $error)
    <x-shop-alert type="danger" msg="{{ $error }}" class="mt-4" />
  @endforeach
@endif

<div class="card mb-lg-4 mb-2 order-head">
  <div class="card-header d-flex align-items-center justify-content-between">
    <h6 class="card-title">{{ __('shop/account/order_info.order_details') }}</h6>
    <div>
      @if ($order->status == 'unpaid')
        <a href="{{ shop_route('orders.pay', $order->number) }}" class="btn btn-primary btn-sm nowrap">{{ __('shop/account/order_info.to_pay') }}</a>
        <button class="btn btn-outline-secondary btn-sm cancel-order" type="button">{{ __('shop/account/order_info.cancel') }}</button>
      @endif
      @if ($order->status == 'shipped')
        <button class="btn btn-primary btn-sm shipped-ed" type="button">{{ __('shop/account/order_info.confirm_receipt') }}</button>
      @endif
      @hook('account.order.info.order_details.top.btns')
    </div>
  </div>
  <div class="card-body">
    <div class="bg-light p-2 table-responsive">
      @if (!is_mobile())
        <table class="table table-borderless mb-0">
          <thead>
            <tr>
              <th class="nowrap">{{ __('shop/account/order_info.order_number') }}</th>
              <th class="nowrap">{{ __('shop/account/order_info.order_date') }}</th>
              <th class="nowrap">{{ __('shop/account/order_info.state') }}</th>
              <th class="nowrap">{{ __('shop/account/order_info.order_amount') }}</th>
              <th class="nowrap">{{ __('shop/checkout.payment_method') }}</th>
              <th class="nowrap">{{ __('shop/checkout.delivery_method') }}</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td>{{ $order->number }}</td>
              <td class="nowrap">{{ $order->created_at }}</td>
              <td class="nowrap">{{$order->status_format}}</td>
              <td>{{ currency_format($order->total, $order->currency_code, $order->currency_value) }}</td>
              <td>{{ $order->payment_method_name }}</td>
              <td>{{ $order->shipping_method_name }}</td>
            </tr>
          </tbody>
        </table>
      @else
        <div>
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
          <div class="d-flex justify-content-between mb-2">
            <div>{{ __('shop/account/order_info.order_amount') }}</div>
            <div class="fw-bold">{{ currency_format($order->total, $order->currency_code, $order->currency_value) }}</div>
          </div>
          <div class="d-flex justify-content-between mb-2">
            <div>{{ __('shop/checkout.payment_method') }}</div>
            <div class="fw-bold">{{ $order->payment_method_name }}</div>
          </div>
          <div class="d-flex justify-content-between">
            <div>{{ __('shop/checkout.delivery_method') }}</div>
            <div class="fw-bold">{{ $order->shipping_method_name }}</div>
          </div>
        </div>
      @endif
    </div>
  </div>
</div>
@hookwrapper('account.order_info.address_info')
<div class="card mb-lg-4 mb-2">
  <div class="card-header"><h6 class="card-title">{{ __('order.address_info') }}</h6></div>
  <div class="card-body">
    <table class="table ">
      <thead class="">
        <tr>
          @if ($order->shipping_country)
            <th>{{ __('order.shipping_address') }}</th>
          @endif
          <th>{{ __('order.payment_address') }}</th>
        </tr>
      </thead>
      <tbody>
        <tr>
          @if ($order->shipping_country)
            <td>
              <div>
                {{ __('address.name') }}：{{ $order->shipping_customer_name }}
                @if ($order->shipping_telephone)
                ({{ $order->shipping_telephone }})
                @endif
              </div>
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
          @endif
          <td>
            <div>
              {{ __('address.name') }}：{{ $order->payment_customer_name }}
              @if ($order->payment_telephone)
              ({{ $order->payment_telephone }})
              @endif
            </div>
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
<div class="card mb-lg-4 mb-2">
  <div class="card-header">
    <h6 class="card-title">{{ __('shop/account/order_info.order_items') }}</h6>
  </div>
  <div class="card-body">
    @foreach ($order->orderProducts as $product)
      <div class="product-list">
        <div class="d-flex">
          <div class="left border d-flex justify-content-center align-items-center wh-80"><img src="{{ image_resize($product->image) }}" class="img-fluid"></div>
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
            class="btn btn-outline-primary btn-sm">{{ __('shop/account/order_info.apply_after_sales') }}</a>
        @endif
      </div>
    @endforeach
  </div>
</div>

<div class="card mb-lg-4 mb-2">
  <div class="card-header">
    <h6 class="card-title">{{ __('shop/account/order_info.order_total') }}</h6>
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

@if ($order->comment)
  <div class="card mb-lg-4 mb-2">
    <div class="card-header">
      <h6 class="card-title">{{ __('order.comment') }}</h6>
    </div>
    <div class="card-body">
      {{ $order->comment }}
    </div>
  </div>
@endif

@foreach ($html_items as $item)
  {!! $item !!}
@endforeach

@hook('account.order_info.after')

@if ($order->orderShipments->count())
  @hookwrapper('account.order_info.shipments')
  <div class="card mb-lg-4 mb-2">
    <div class="card-header"><h6 class="card-title">{{ __('order.order_shipments') }}</h6></div>
    <div class="card-body">
      <div class="table-push">
        <table class="table ">
          <thead class="">
            <tr>
              <th>{{ __('order.express_company') }}</th>
              <th>{{ __('order.express_number') }}</th>
              <th>{{ __('order.history_created_at') }}</th>
            </tr>
          </thead>
          <tbody>
            @foreach ($order->orderShipments as $ship)
            <tr>
              <td>{{ $ship->express_company }}</td>
              <td>{{ $ship->express_number }}</td>
              <td>{{ $ship->created_at }}</td>
            </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    </div>
  </div>
  @endhookwrapper
@endif

@if ($order->orderHistories->count())
  <div class="card mb-lg-4 mb-2">
    <div class="card-header">
      <h6 class="card-title">{{ __('shop/account/order_info.order_status') }}</h6>
    </div>
    <div class="card-body">
      <table class="table ">
        <thead class="">
          <tr>
            <th>{{ __('shop/account/order_info.state') }}</th>
            <th>{{ __('shop/account/order_info.remark') }}</th>
            <th>{{ __('shop/account/order_info.update_time') }}</th>
          </tr>
        </thead>
        <tbody>
          @foreach ($order->orderHistories as $orderHistory)
            <tr>
              <td>{{ $orderHistory->status_format }}</td>
              <td><span class="fw-bold">{{ $orderHistory->comment }}</span></td>
              <td><span class="fw-bold">{{ $orderHistory->created_at }}</span></td>
            </tr>
          @endforeach
        </tbody>
      </table>
    </div>
  </div>
@endif

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
