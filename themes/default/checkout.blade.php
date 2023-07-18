@extends('layout.master')

@section('body-class', 'page-checkout')

@push('header')
  <script src="{{ asset('vendor/vue/2.7/vue' . (!config('app.debug') ? '.min' : '') . '.js') }}"></script>
  <script src="{{ asset('vendor/scrolltofixed/jquery-scrolltofixed-min.js') }}"></script>
  <script src="{{ asset('vendor/element-ui/index.js') }}"></script>
  <link rel="stylesheet" href="{{ asset('vendor/element-ui/index.css') }}">
@endpush

@section('content')
  <x-shop-breadcrumb type="static" value="checkout.index" />

  <div class="container">
    <div class="row mt-1 justify-content-center">
      <div class="col-12 col-md-9">@include('shared.steps', ['steps' => 2])</div>
    </div>

    <div class="row mt-5">
      <div class="col-12 col-md-8 left-column">
        <div class="card shadow-sm">
          <div class="card-body p-lg-4">
            @hook('checkout.body.header')

            @include('checkout._address')

            <div class="checkout-black">
              <h5 class="checkout-title">{{ __('shop/checkout.payment_method') }}</h5>
              <div class="radio-line-wrap">
                @foreach ($payment_methods as $payment)
                  <div class="radio-line-item {{ $payment['code'] == $current['payment_method_code'] ? 'active' : '' }}" data-key="payment_method_code" data-value="{{ $payment['code'] }}">
                    <div class="left">
                      <span class="radio"></span>
                      <img src="{{ $payment['icon'] }}" class="img-fluid">
                    </div>
                    <div class="right ms-2">
                      <div class="title">{{ $payment['name'] }}</div>
                      <div class="sub-title">{!! $payment['description'] !!}</div>
                    </div>
                  </div>
                @endforeach
              </div>
            </div>

            <div class="checkout-black">
              <h5 class="checkout-title">{{ __('shop/checkout.delivery_method') }}</h5>
              <div class="radio-line-wrap" id="shipping-methods-wrap">
                @foreach ($shipping_methods as $methods)
                  @foreach ($methods['quotes'] as $shipping)
                  <div class="radio-line-item {{ $shipping['code'] == $current['shipping_method_code'] ? 'active':'' }}" data-key="shipping_method_code" data-value="{{ $shipping['code'] }}">
                    <div class="left">
                      <span class="radio"></span>
                      <img src="{{ $shipping['icon'] }}" class="img-fluid">
                    </div>
                    <div class="right ms-2">
                      <div class="title">{{ $shipping['name'] }}</div>
                      <div class="sub-title">{!! $shipping['description'] !!}</div>
                      <div class="mt-2">{!! $shipping['html'] ?? '' !!}</div>
                    </div>
                  </div>
                  @endforeach
                @endforeach
              </div>
            </div>

            @hook('checkout.bottom')
          </div>
        </div>
      </div>

      <div class="col-12 col-md-4 right-column">
        <div class="x-fixed-top">
          @if (!current_customer())
            <div class="card total-wrap mb-4 p-lg-4 shadow-sm">
              <div class="card-header">
                <h5 class="mb-0">{{ __('shop/login.login_and_sign') }}</h5>
              </div>
              <div class="card-body">
                <button class="btn btn-outline-dark guest-checkout-login"><i class="bi bi-box-arrow-in-right me-2"></i>{{ __('shop/login.login_and_sign') }}</button>
              </div>
            </div>
          @endif

          <div class="card total-wrap p-lg-4 shadow-sm">
            <div class="card-header d-flex align-items-center justify-content-between">
              <h5 class="mb-0">{{ __('shop/checkout.cart_totals') }}</h5>
              <span class="rounded-circle bg-primary">{{ $carts['quantity'] }}</span>
            </div>
            <div class="card-body">
              @hookwrapper('checkout.products')
              <div class="products-wrap">
                @foreach ($carts['carts'] as $cart)
                  <div class="item">
                    <div class="image">
                      <div class="img border d-flex align-items-center justify-content-center wh-40 me-2">
                        <img src="{{ image_resize($cart['image'], 100, 100) }}" class="img-fluid">
                      </div>
                      <div class="name">
                        <div title="{{ $cart['name'] }}" class="text-truncate-2">{{ $cart['name'] }}</div>
                        @if ($cart['variant_labels'])
                          <div class="text-muted mt-1">{{ $cart['variant_labels'] }}</div>
                        @endif
                      </div>
                    </div>
                    <div class="price text-end">
                      <div>{{ $cart['price_format'] }}</div>
                      <div class="quantity">x {{ $cart['quantity'] }}</div>
                    </div>
                  </div>
                @endforeach
              </div>
              @endhookwrapper
              <ul class="totals">
                @foreach ($totals as $total)
                  <li><span>{{ $total['title'] }}</span><span>{{ $total['amount_format'] }}</span></li>
                @endforeach
              </ul>
              <div class="d-grid gap-2 mt-3">
                @hookwrapper('checkout.confirm')
                <button class="btn btn-primary fw-bold fs-5" type="button" id="submit-checkout">{{ __('shop/checkout.submit_order') }}</button>
                @endhookwrapper
              </div>

              @hook('checkout.total.footer')
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  @hook('checkout.footer')
@endsection

@push('add-scripts')
<script>
  $(document).ready(function() {
    $(document).on('click', '.radio-line-item', function(event) {
      const key = $(this).data('key');
      const value = $(this).data('value');
      updateCheckout(key, value, () => {
        $(this).addClass('active').siblings().removeClass('active')
      })
    });

    $('#submit-checkout').click(function(event) {
      if (!config.isLogin && checkoutAddressApp.source.guest_shipping_address === null) {
        layer.msg('{{ __('shop/checkout.error_payment_address') }}', ()=>{})
        return;
      }

      if (config.isLogin && !checkoutAddressApp.form.payment_address_id) {
        layer.msg('{{ __('shop/checkout.error_payment_address') }}', ()=>{})
        return;
      }

      $http.post('/checkout/confirm').then((res) => {
        location = 'orders/' + res.number + '/pay?type=create'
      })
    });

    $('.guest-checkout-login').click(function(event) {
      bk.openLogin();
    });
  });

  const updateCheckout = (key, value, callback) => {
    $http.put('/checkout', {[key]: value}).then((res) => {
      if (res.status == 'fail') {
        layer.msg(res.message, ()=>{})
        return;
      }

      updateTotal(res.totals)

      if (typeof callback === 'function') {
        callback(res)
      }
    })
  }

  const updateTotal = (totals) => {
    let html = '';

    totals.forEach((item) => {
      html += `<li><span>${item.title}</span><span>${item.amount_format}</span></li>`
    })

    $('ul.totals').html(html);
  }

  const updateShippingMethods = (data, shipping_method_code) => {
    let html = '';

    data.forEach((methods) => {
      methods.quotes.forEach((quote) => {
        html += `<div class="radio-line-item d-flex align-items-center ${shipping_method_code == quote.code ? 'active' : ''}" data-key="shipping_method_code" data-value="${quote.code}">
          <div class="left">
            <span class="radio"></span>
            <img src="${quote.icon}" class="img-fluid">
          </div>
          <div class="right ms-3">
            <div class="title">${quote.name}</div>
            <div class="sub-title">${quote.description}</div>
            <div class="mt-2">${quote.html || ''}</div>
          </div>
        </div>`;
      })
    })

    $('#shipping-methods-wrap').html(html);
  }
</script>
@endpush
