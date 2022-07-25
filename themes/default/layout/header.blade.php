<header>
  <div class="top-wrap">
    <div class="container d-flex justify-content-between align-items-center">
      <div class="left d-flex align-items-center">
        <div class="dropdown">
          <a class="btn dropdown-toggle ps-0" href="#" role="button" id="currency-dropdown" data-toggle="dropdown"
            aria-expanded="false">
            $ USD
          </a>

          <div class="dropdown-menu" aria-labelledby="currency-dropdown">
            <a class="dropdown-item" href="#">Action</a>
            <a class="dropdown-item" href="#">Another action</a>
            <a class="dropdown-item" href="#">Something else here</a>
          </div>
        </div>
        <div class="dropdown">
          <a class="btn dropdown-toggle" href="#" role="button" id="language-dropdown" data-toggle="dropdown"
            aria-expanded="false">
            Language
          </a>

          <div class="dropdown-menu" aria-labelledby="language-dropdown">
            <a class="dropdown-item" href="#">Action</a>
            <a class="dropdown-item" href="#">Another action</a>
            <a class="dropdown-item" href="#">Something else here</a>
          </div>
        </div>
      </div>
      <div class="right nav">
        <a href="" class="px-2">Delivery</a>
        <a href="" class="px-2">Help</a>
        <span class="px-2">028-0000000</span>
      </div>
    </div>
  </div>

  <div class="header-content py-3">
    <div class="container navbar-expand-lg">
      <div class="logo"><a href="{{ shop_route('home.index') }}"><img src="{{ asset('image/logo.png') }}" class="img-fluid"></a></div>
      <div class="menu-wrap">
        <ul class="navbar-nav mx-auto">
          @foreach ($categories as $category)
          <li class="dropdown">
            <a
              class="nav-link {{ (isset($category['children']) and $category['children']) ? 'dropdown-toggle' : '' }}"
              href="{{ $category['url'] }}">
              {{ $category['name'] }}
            </a>
            @if (isset($category['children']) and $category['children'])
            <ul class="dropdown-menu">
              @forelse ($category['children'] as $child)
                <li><a href="{{ $child['url'] }}" class="dropdown-item">{{ $child['name'] }}</a></li>
              @endforeach
            </ul>
            @endif
          </li>
          @endforeach
        </ul>

          {{-- <a href="{{ shop_route('categories.show', $category) }}">{{ $category->description->name }}</a> --}}
      </div>
      <div class="right-btn">
        <ul class="navbar-nav flex-row">
          <li class="nav-item"><a href="" class="nav-link"><i class="iconfont">&#xe8d6;</i></a></li>
          <li class="nav-item"><a href="{{ shop_route('account.wishlist.index') }}" class="nav-link"><i class="iconfont">&#xe662;</i></a></li>
          <li class="nav-item dropdown">
            <a href="{{ shop_route('account.index') }}" class="nav-link"><i class="iconfont">&#xe619;</i></a>
            <ul class="dropdown-menu dropdown-menu-end">
              @auth('web_shop')
                <li class="dropdown-item"><h6 class="mb-0">Pu Shuo</h6></li>
                <li><hr class="dropdown-divider"></li>
                <li><a href="{{ shop_route('account.index') }}" class="dropdown-item"><i class="bi bi-person me-1"></i> 个人中心</a></li>
                <li><a href="{{ shop_route('account.order.index') }}" class="dropdown-item"><i class="bi bi-clipboard-check me-1"></i> 我的订单</a></li>
                <li><a href="{{ shop_route('account.wishlist.index') }}" class="dropdown-item"><i class="bi bi-heart me-1"></i> 我的收藏</a></li>
                <li><hr class="dropdown-divider"></li>
                <li><a href="{{ shop_route('logout') }}" class="dropdown-item"><i class="bi bi-box-arrow-left me-1"></i> 退出登录</a></li>
              @else
                <li><a href="{{ shop_route('login.index') }}" class="dropdown-item"><i class="bi bi-box-arrow-right me-1"></i> 登录/注册</a></li>
              @endauth
            </ul>
          </li>
          <li class="nav-item">
            {{-- <a href="javascript:vido(0)" data-bs-toggle="offcanvas" data-bs-target="#offcanvas-right-cart" aria-controls="offcanvas-right-cart" class="nav-link"><i class="iconfont">&#xe634;</i></a> --}}
            <a class="nav-link" data-bs-toggle="offcanvas" href="#offcanvas-right-cart" role="button" aria-controls="offcanvasExample">
              <i class="iconfont">&#xe634;</i>
              <div class="navbar-icon-link-badge"></div>
            </a>
          </li>
        </ul>
      </div>
    </div>
  </div>
  <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvas-right-cart" aria-labelledby="offcanvasRightLabel">
    <div class="offcanvas-header">
      <h5 id="offcanvasRightLabel" class="mx-auto mb-0">您的购物车</h5>
      <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body">
      <div class="offcanvas-right-products">
  {{--       @for ($i = 0; $i < 20; $i++)
          <div class="product-list d-flex align-items-center">
            <div class="left"><img src="https://via.placeholder.com/140x140.png/eeeeee" calss="img-fluid"></div>
            <div class="right flex-grow-1">
              <div class="name fs-sm fw-bold mb-2">测试商品名称事实上</div>
              <div class="price mb-2">22.22</div>
              <div class="product-bottom d-flex justify-content-between align-items-center">
                @include('shared.quantity', ['quantity' => '1'])
                <span>删除</span>
              </div>
            </div>
          </div>
        @endfor --}}
      </div>
    </div>
    <div class="offcanvas-footer">
      <div class="d-flex justify-content-between align-items-center mb-2 p-4 bg-light">
        <strong>小计（<span class="offcanvas-right-cart-count"></span>）</strong>
        <strong class="ms-auto offcanvas-right-cart-amount"></strong>
      </div>
      <div class="p-4">
        <a href="{{ shop_route('checkout.index') }}" class="btn w-100 btn-dark">去结账</a>
        <a href="{{ shop_route('carts.index') }}" class="btn w-100 btn-outline-dark mt-2">查看购物车</a>
      </div>
    </div>
  </div>
</header>

