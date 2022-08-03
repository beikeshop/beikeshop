<div class="col-12 col-md-3">
  <div class="account-sides-info">
    <div class="head">
      <div class="portrait"><img src="{{ image_resize($customer->avatar, 200, 200) }}" class="img-fluid"></div>
      <div class="account-name">{{ $customer->name }}</div>
      <div class="account-email">{{ $customer->email }}</div>
    </div>
    <nav class="list-group account-links">
      <a class="list-group-item d-flex justify-content-between align-items-center active" href="{{ shop_route('account.index') }}">
        <span>个人中心</span></a>
      <a class="list-group-item d-flex justify-content-between align-items-center" href="{{ shop_route('account.edit.index') }}">
        <span>修改个人信息</span></a>
      <a class="list-group-item d-flex justify-content-between align-items-center" href="{{ shop_route('account.order.index') }}">
        <span>我的订单</span><span class="px-3 badge rounded-pill bg-dark">5</span></a>
      <a class="list-group-item d-flex justify-content-between align-items-center" href="{{ shop_route('addresses.index') }}">
        <span>我的地址</span><span class="px-3 badge rounded-pill bg-dark">5</span></a>
      <a class="list-group-item d-flex justify-content-between align-items-center" href="{{ shop_route('account.wishlist.index') }}">
        <span>我的收藏</span></a>
      <a class="list-group-item d-flex justify-content-between align-items-center" href="{{ shop_route('logout') }}">
        <span>退出登录</span></a>
    </nav>
  </div>
</div>