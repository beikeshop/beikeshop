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
            <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">{{ $category['name'] }}</a>
            @if ($category['children'])
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
          <li class="nav-item"><a href="" class="nav-link"><i class="iconfont">&#xe662;</i></a></li>
          <li class="nav-item dropdown">
            <a href="{{ shop_route('account.index') }}" class="nav-link"><i class="iconfont">&#xe619;</i></a>
            <ul class="dropdown-menu dropdown-menu-end">
              @auth('web_shop')
                <li class="dropdown-item"><h6 class="mb-0">Pu Shuo</h6></li>
                <li><hr class="dropdown-divider"></li>
                <li><a href="{{ shop_route('account.index') }}" class="dropdown-item"><i class="bi bi-person me-1"></i> 个人中心</a></li>
                <li><a href="{{ shop_route('account.index') }}" class="dropdown-item"><i class="bi bi-clipboard-check me-1"></i> 我的订单</a></li>
                <li><a href="{{ shop_route('account.index') }}" class="dropdown-item"><i class="bi bi-heart me-1"></i> 我的收藏</a></li>
                <li><hr class="dropdown-divider"></li>
                <li><a href="{{ shop_route('logout') }}" class="dropdown-item"><i class="bi bi-box-arrow-left me-1"></i> 退出登录</a></li>
              @else
                <li><a href="{{ shop_route('login.index') }}" class="dropdown-item"><i class="bi bi-box-arrow-right me-1"></i> 登录/注册</a></li>
              @endauth
            </ul>
          </li>
          <li class="nav-item">
            <a href="{{ shop_route('carts.index') }}" class="nav-link"><i class="iconfont">&#xe634;</i></a>
          </li>
        </ul>
      </div>
    </div>
  </div>
</header>
