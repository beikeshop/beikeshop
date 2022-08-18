<div class="col-12 col-md-3">
  <div class="account-sides-info">
    <div class="head">
      <div class="portrait"><img src="{{ image_resize($customer->avatar, 200, 200) }}" class="img-fluid"></div>
      <div class="account-name">{{ $customer->name }}</div>
      <div class="account-email">{{ $customer->email }}</div>
    </div>
    <nav class="list-group account-links">
      <a class="list-group-item d-flex justify-content-between align-items-center active" href="{{ shop_route('account.index') }}">
        <span>{{ __('shop/account.index') }}</span></a>
      <a class="list-group-item d-flex justify-content-between align-items-center" href="{{ shop_route('account.edit.index') }}">
        <span>修改个人信息</span></a>
      <a class="list-group-item d-flex justify-content-between align-items-center" href="{{ shop_route('account.order.index') }}">
        <span>我的订单</span></a>
      <a class="list-group-item d-flex justify-content-between align-items-center" href="{{ shop_route('account.addresses.index') }}">
        <span>我的地址</span></a>
      <a class="list-group-item d-flex justify-content-between align-items-center" href="{{ shop_route('account.wishlist.index') }}">
        <span>我的收藏</span></a>
      <a class="list-group-item d-flex justify-content-between align-items-center" href="{{ shop_route('account.rma.index') }}">
        <span>我的售后</span></a>
      <a class="list-group-item d-flex justify-content-between align-items-center" href="{{ shop_route('logout') }}">
        <span>退出登录</span></a>
    </nav>
  </div>
</div>
