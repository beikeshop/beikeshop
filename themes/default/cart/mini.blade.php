<div class="offcanvas-header">
  <h5 id="offcanvasRightLabel" class="mx-auto mb-0">您的购物车</h5>
  <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
</div>
<div class="offcanvas-body">
  <div class="offcanvas-right-products">
    @foreach ($carts as $cart)
    <div class="product-list d-flex align-items-center">
      <div class="left"><img src="{{ $cart['image'] }}" calss="img-fluid"></div>
      <div class="right flex-grow-1">
        <div class="name fs-sm fw-bold mb-2">{{ $cart['name'] }}</div>
        <div class="product-bottom d-flex justify-content-between align-items-center">
          <div class="price">{{ $cart['price_format'] }} <span class="text-muted">x {{ $cart['quantity'] }}<span></div>
          <span class="offcanvas-products-delete" data-id="{{ $cart['cart_id'] }}"><i class="bi bi-x-lg"></i> 删除</span>
        </div>
      </div>
    </div>
    @endforeach
  </div>
</div>
<div class="offcanvas-footer">
  <div class="d-flex justify-content-between align-items-center mb-2 p-4 bg-light">
    <strong>小计（<span class="offcanvas-right-cart-count">{{ $quantity }}</span>）</strong>
    <strong class="ms-auto offcanvas-right-cart-amount">{{ $amount_format }}</strong>
  </div>
  <div class="p-4">
    <a href="{{ shop_route('checkout.index') }}" class="btn w-100 btn-dark">去结账</a>
    <a href="{{ shop_route('carts.index') }}" class="btn w-100 btn-outline-dark mt-2">查看购物车</a>
  </div>
</div>