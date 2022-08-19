<div class="col-12 col-md-3">
  <div class="account-sides-info">
    <div class="head">
      <div class="portrait"><img src="{{ image_resize($customer->avatar, 200, 200) }}" class="img-fluid"></div>
      <div class="account-name">{{ $customer->name }}</div>
      <div class="account-email">{{ $customer->email }}</div>
    </div>

    <nav class="list-group account-links">
      <a class="list-group-item d-flex justify-content-between align-items-center {{ equal_route('shop.account.index') ? 'active' : '' }}"
        href="{{ shop_route('account.index') }}">
        <span>{{ __('shop/account.index') }}</span></a>
      <a class="list-group-item d-flex justify-content-between align-items-center {{ equal_route('shop.account.edit.index') ? 'active' : '' }}"
        href="{{ shop_route('account.edit.index') }}">
        <span>{{ __('shop/account.edit.index') }}</span></a>
      <a class="list-group-item d-flex justify-content-between align-items-center {{ equal_route('shop.account.order.index') || equal_route('shop.account.order.show') ? 'active' : '' }}"
        href="{{ shop_route('account.order.index') }}">
        <span>{{ __('shop/account.order.index') }}</span></a>
      <a class="list-group-item d-flex justify-content-between align-items-center {{ equal_route('shop.account.addresses.index') ? 'active' : '' }}"
        href="{{ shop_route('account.addresses.index') }}">
        <span>{{ __('shop/account.addresses.index') }}</span></a>
      <a class="list-group-item d-flex justify-content-between align-items-center {{ equal_route('shop.account.wishlist.index') ? 'active' : '' }}"
        href="{{ shop_route('account.wishlist.index') }}">
        <span>{{ __('shop/account.wishlist.index') }}</span></a>
      <a class="list-group-item d-flex justify-content-between align-items-center {{ equal_route('shop.account.rma.index') || equal_route('shop.account.rma.show') ? 'active' : '' }}"
        href="{{ shop_route('account.rma.index') }}">
        <span>{{ __('shop/account.rma.index') }}</span></a>
      <a class="list-group-item d-flex justify-content-between align-items-center" href="{{ shop_route('logout') }}">
        <span>{{ __('common.sign_out') }}</span></a>
    </nav>
  </div>
</div>
