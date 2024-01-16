<header>
  @hook('header.before')
  <div class="top-wrap">
    <div class="container-fluid d-flex justify-content-between align-items-center">
      <div class="left d-flex align-items-center">
        @hookwrapper('header.top.currency')
        @if (currencies()->count() > 1)
          <div class="dropdown">
            <a class="btn dropdown-toggle ps-0" href="javascript:void(0)" role="button" id="currency-dropdown" data-toggle="dropdown"
              aria-expanded="false">
              @foreach (currencies() as $currency)
                @if ($currency->code == current_currency_code())
                  @if ($currency->symbol_left)
                  {{ $currency->symbol_left }}
                  @endif
                  {{ $currency->name }}
                  @if ($currency->symbol_right)
                  {{ $currency->symbol_right }}
                  @endif
                @endif
              @endforeach
            </a>

            <div class="dropdown-menu" aria-labelledby="currency-dropdown">
              @foreach (currencies() as $currency)
                <a class="dropdown-item"
                  href="{{ shop_route('currency.switch', [$currency->code]) }}">
                  @if ($currency->symbol_left)
                  {{ $currency->symbol_left }}
                  @endif
                  {{ $currency->name }}
                  @if ($currency->symbol_right)
                  {{ $currency->symbol_right }}
                  @endif
                  </a>
              @endforeach
            </div>
          </div>
        @endif
        @endhookwrapper

        @hookwrapper('header.top.language')
        @if (count($languages) > 1)
          <div class="dropdown">
            <a class="btn dropdown-toggle" href="javascript:void(0)" role="button" id="language-dropdown" data-toggle="dropdown"
              aria-expanded="false">
              {{ current_language()->name }}
            </a>

            <div class="dropdown-menu" aria-labelledby="language-dropdown">
              @foreach ($languages as $language)
                <a class="dropdown-item" href="{{ shop_route('lang.switch', [$language->code]) }}">
                  {{ $language->name }}
                </a>
              @endforeach
            </div>
          </div>
        @endif
        @endhookwrapper

        @hook('header.top.left')
      </div>

      @hook('header.top.language.after')

      <div class="right nav">
        @if (system_setting('base.telephone', ''))
          @hookwrapper('header.top.telephone')
          <div class="my-auto"><i class="bi bi-telephone-forward me-2"></i> {{ system_setting('base.telephone') }}</div>
          @endhookwrapper
        @endif

        @hook('header.top.right')
      </div>
    </div>
  </div>

  <div class="header-content d-none d-lg-block">
    <div class="container-fluid navbar-expand-lg">
      @hookwrapper('header.menu.logo')
      <div class="logo"><a href="{{ shop_route('home.index') }}">
          <img src="{{ image_origin(system_setting('base.logo')) }}" class="img-fluid"></a>
      </div>
      @endhookwrapper
      <div class="menu-wrap">
        @include('shared.menu-pc')
      </div>
      <div class="right-btn">
        <ul class="navbar-nav flex-row">
          @hookwrapper('header.menu.icon')
          <li class="nav-item"><a href="#offcanvas-search-top" data-bs-toggle="offcanvas"
              aria-controls="offcanvasExample" class="nav-link"><i class="iconfont">&#xe8d6;</i></a></li>
          <li class="nav-item"><a href="{{ shop_route('account.wishlist.index') }}" class="nav-link"><i
                class="iconfont">&#xe662;</i></a></li>
          <li class="nav-item dropdown">
            <a href="{{ shop_route('account.index') }}" class="nav-link"><i class="iconfont">&#xe619;</i></a>
            <ul class="dropdown-menu">
              @auth('web_shop')
                <li class="dropdown-item">
                  <h6 class="mb-0">{{ current_customer()->name }}</h6>
                </li>
                <li>
                  <hr class="dropdown-divider opacity-100">
                </li>
                <li><a href="{{ shop_route('account.index') }}" class="dropdown-item"><i class="bi bi-person me-1"></i>
                  {{ __('shop/account.index') }}</a></li>
                <li><a href="{{ shop_route('account.order.index') }}" class="dropdown-item"><i
                      class="bi bi-clipboard-check me-1"></i> {{ __('shop/account/order.index') }}</a></li>
                <li><a href="{{ shop_route('account.wishlist.index') }}" class="dropdown-item"><i
                      class="bi bi-heart me-1"></i> {{ __('shop/account/wishlist.index') }}</a></li>
                <li>
                  <hr class="dropdown-divider opacity-100">
                </li>
                <li><a href="{{ shop_route('logout') }}" class="dropdown-item"><i class="bi bi-box-arrow-left me-1"></i>
                    {{ __('common.sign_out') }}</a></li>
              @else
                <li><a href="{{ shop_route('login.index') }}" class="dropdown-item"><i
                      class="bi bi-box-arrow-right me-1"></i>{{ __('shop/login.login_and_sign') }}</a></li>
              @endauth
            </ul>
          </li>
          @endhookwrapper
          <li class="nav-item">
            {{-- <a class="nav-link position-relative" {{ !equal_route('shop.carts.index') ? 'data-bs-toggle=offcanvas' : '' }}
              href="{{ !equal_route('shop.carts.index') ? '#offcanvas-right-cart' : 'javascript:void(0);' }}" role="button"
              aria-controls="offcanvasExample">
              <i class="iconfont">&#xe634;</i>
              <span class="cart-badge-quantity"></span>
            </a> --}}
            <a class="nav-link position-relative btn-right-cart {{ equal_route('shop.carts.index') ? 'page-cart' : '' }}" href="javascript:void(0);" role="button">
              <i class="iconfont">&#xe634;</i>
              <span class="cart-badge-quantity"></span>
            </a>
          </li>
        </ul>
      </div>
    </div>
  </div>

  <div class="header-mobile d-lg-none">
    <div class="mobile-content">
      <div class="left">
        <div class="mobile-open-menu"><i class="bi bi-list"></i></div>
        <div class="mobile-open-search" href="#offcanvas-search-top" data-bs-toggle="offcanvas" aria-controls="offcanvasExample">
          <i class="iconfont">&#xe8d6;</i>
        </div>
      </div>
      <div class="center"><a href="{{ shop_route('home.index') }}">
        <img src="{{ image_origin(system_setting('base.logo')) }}" class="img-fluid"></a>
      </div>
      <div class="right">
        <a href="{{ shop_route('account.index') }}" class="nav-link mb-account-icon">
          <i class="iconfont">&#xe619;</i>
          @if (strstr(current_route(), 'shop.account'))
            <span></span>
          @endif
        </a>
        <a href="{{ shop_route('carts.index') }}" class="nav-link ms-3 m-cart position-relative"><i class="iconfont">&#xe634;</i> <span class="cart-badge-quantity"></span></a>
      </div>
    </div>
  </div>
  <div class="offcanvas offcanvas-start" tabindex="-1" id="offcanvas-mobile-menu">
    <div class="offcanvas-header">
      <h5 class="offcanvas-title" id="offcanvasWithBothOptionsLabel">{{ __('common.menu') }}</h5>
      <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body mobile-menu-wrap">
      @include('shared.menu-mobile')
    </div>
  </div>

  <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvas-right-cart" aria-labelledby="offcanvasRightLabel"></div>

  <div class="offcanvas offcanvas-top" tabindex="-1" id="offcanvas-search-top" aria-labelledby="offcanvasTopLabel">
    <div class="offcanvas-header">
      <input type="text" class="form-control input-group-lg border-0 fs-4" focus placeholder="{{ __('common.input') }}" value="{{ request('keyword') }}">
      <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
  </div>
  @hook('header.after')
</header>
