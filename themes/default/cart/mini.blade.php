<div class="offcanvas-header">
  <h5 id="offcanvasRightLabel" class="mx-auto mb-0">{{ __('shop/carts.mini') }}</h5>
  <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
</div>
<div class="offcanvas-body">
  @if ($carts)
    <div class="offcanvas-right-products">
      @foreach ($carts as $cart)
        <div class="product-list d-flex align-items-center">
          <div class="left"><a href="{{ shop_route('products.show', $cart['product_id']) }}" class="d-flex justify-content-between align-items-center h-100"><img src="{{ $cart['image'] }}" class="img-fluid"></a></div>
          <div class="right flex-grow-1">
            <a href="{{ shop_route('products.show', $cart['product_id']) }}" class="name fs-sm fw-bold mb-3 text-dark" title="{{ $cart['name'] }}">{{ $cart['name'] }}</a>
            <div class="product-bottom d-flex justify-content-between align-items-center">
              <div class="price">{{ $cart['price_format'] }} <span class="text-muted">x {{ $cart['quantity'] }}<span>
              </div>
              <span class="offcanvas-products-delete" data-id="{{ $cart['cart_id'] }}"><i class="bi bi-x-lg"></i>
                {{ __('common.delete') }}</span>
            </div>
          </div>
        </div>
      @endforeach
    </div>
  @else
    <div class="d-flex justify-content-center align-items-center flex-column">
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
  @endif
</div>

@if ($carts)
  <div class="offcanvas-footer">
    <div class="d-flex justify-content-between align-items-center mb-2 p-4 bg-light">
      <strong>{{ __('shop/carts.subtotal') }}（<span class="offcanvas-right-cart-count">{{ $quantity }}</span>）</strong>
      <strong class="ms-auto offcanvas-right-cart-amount">{{ $amount_format }}</strong>
    </div>
    <div class="p-4">
      <a href="{{ shop_route('checkout.index') }}" class="btn w-100 btn-dark">{{ __('shop/carts.to_checkout') }}</a>
      <a href="{{ shop_route('carts.index') }}" class="btn w-100 btn-outline-dark mt-2">{{ __('shop/carts.check_cart') }}</a>
    </div>
  </div>
@endif
