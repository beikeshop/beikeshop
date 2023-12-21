<div class="offcanvas-header">
  <h5 id="offcanvasRightLabel" class="mx-auto mb-0">{{ __('shop/carts.mini') }}</h5>
  <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
</div>
<div class="offcanvas-body pt-0">
  @php $check = 0 @endphp

  @if ($carts)
  <div class="offcanvas-right-products">
    @foreach ($carts as $cart)
      @if ($cart['selected']) @php $check = $check + 1 @endphp @endif
      <div class="product-list d-flex align-items-center">
        <div class="select-wrap">
          <i class="bi {{ $cart['selected'] ? 'bi-check-circle-fill' : 'bi-circle' }}" data-id="{{ $cart['cart_id'] }}"></i>
        </div>
        <div class="product-info d-flex align-items-center">
          <div class="left"><a href="{{ shop_route('products.show', $cart['product_id']) }}" class="d-flex justify-content-center align-items-center h-100"><img src="{{ $cart['image_url'] ?: image_resize('', 160, 160) }}" class="img-fluid"></a></div>
          <div class="right flex-grow-1">
            <a href="{{ shop_route('products.show', $cart['product_id']) }}" class="name fs-sm fw-bold mb-2 text-dark text-truncate-2" title="{{ $cart['name'] }}">{{ $cart['name'] }}</a>
            <div class="text-muted mb-1 text-size-min">{{ $cart['variant_labels'] }}</div>
            <div class="product-bottom d-flex justify-content-between align-items-center">
              <div class="price d-flex align-items-center">
                {!! $cart['price_format'] !!} x
                <input type="text" onkeyup="this.value=this.value.replace(/\D/g,'')" onafterpaste="this.value=this.value.replace(/\D/g,'')"
                data-id="{{ $cart['cart_id'] }}" data-sku="{{ $cart['sku_id'] }}" class="form-control p-1" value="{{ $cart['quantity'] }}">
              </div>
              <span class="offcanvas-products-delete" data-id="{{ $cart['cart_id'] }}"><i class="bi bi-x-lg"></i>
                {{ __('common.delete') }}</span>
            </div>
          </div>
        </div>
      </div>
    @endforeach
  </div>
  @endif

  <div class="d-flex justify-content-center align-items-center flex-column empty-cart {{ $carts ? 'd-none' : '' }}">
    <div class="empty-cart-wrap text-center mt-5">
      <div class="empty-cart-icon mb-3">
        <i class="bi bi-cart fs-1"></i>
      </div>
      <div class="empty-cart-text mb-3">
        <h5>{{ __('shop/carts.cart_empty') }}</h5>
        <p class="text-muted">{{ __('shop/carts.go_buy') }}</p>
      </div>
      <div class="empty-cart-action">
        <a href="{{ shop_route('home.index') }}" class="btn btn-primary">{{ __('shop/carts.go_shopping') }}</a>
      </div>
    </div>
  </div>
</div>

@if ($carts)
  <div class="offcanvas-footer">
    <div class="d-flex justify-content-between align-items-center mb-2 p-3 bg-light top-footer">
      <div class="select-wrap all-select d-flex align-items-center">
        <i class="bi {{ $check == count($carts) ? 'bi-check-circle-fill' : 'bi-circle' }}"></i>
        <span class="ms-1 text-secondary">{{ __('common.select_all') }}</span>
      </div>
      <div>
        <span class="text-secondary">{{ __('shop/carts.product_total') }}</span><strong>（<span class="offcanvas-right-cart-count">{{ $quantity }}</span>）</strong>
        <strong class="ms-auto offcanvas-right-cart-amount">{{ $amount_format }}</strong>
      </div>
    </div>
    <div class="p-4">
      <a href="{{ shop_route('checkout.index') }}" class="btn w-100 fw-bold btn-dark to-checkout {{ !$check ? 'disabled' : '' }}">{{ __('shop/carts.to_checkout') }}</a>
      <a href="{{ shop_route('carts.index') }}" class="btn w-100 fw-bold btn-outline-dark mt-2">{{ __('shop/carts.check_cart') }}</a>
    </div>
  </div>
@endif
