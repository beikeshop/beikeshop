<header>
  <div class="top-wrap">
    <div class="container d-flex justify-content-between align-items-center">
      <div class="left d-flex align-items-center">
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
        <div class="dropdown">
          <a class="btn dropdown-toggle" href="javascript:void(0)" role="button" id="language-dropdown" data-toggle="dropdown"
            aria-expanded="false">
            {{-- @if (current_language()->image)
              <img src="{{ image_resize(current_language()->image, 16, 16) }}" class="img-fluid me-1">
            @endif --}}
            {{ current_language()->name }}
          </a>

          <div class="dropdown-menu" aria-labelledby="language-dropdown">
            @foreach ($languages as $language)
              <a class="dropdown-item" href="{{ shop_route('lang.switch', [$language->code]) }}">
                {{-- @if ($language->image)
                <img src="{{ image_resize($language->image, 16, 16) }}" class="img-fluid me-1">
                @endif --}}
                {{ $language->name }}
              </a>
            @endforeach
          </div>
        </div>
      </div>

      @if (system_setting('base.telephone', ''))
      <div class="right nav">
        <span class="px-2"><i class="bi bi-telephone-forward me-2"></i> {{ system_setting('base.telephone') }}</span>
      </div>
      @endif
    </div>
  </div>

  <div class="header-content d-none d-lg-block py-3">
    <div class="container navbar-expand-lg">
      <div class="logo"><a href="{{ shop_route('home.index') }}">
          <img src="{{ image_origin(system_setting('base.logo')) }}" class="img-fluid"></a>
      </div>
      <div class="menu-wrap">
        @if (!is_mobile())
          @include('shared.menu-pc')
        @endif
      </div>
      <div class="right-btn">
        <ul class="navbar-nav flex-row">
          <li class="nav-item"><a href="#offcanvas-search-top" data-bs-toggle="offcanvas"
              aria-controls="offcanvasExample" class="nav-link"><i class="iconfont">&#xe8d6;</i></a></li>
          <li class="nav-item"><a href="{{ shop_route('account.wishlist.index') }}" class="nav-link"><i
                class="iconfont">&#xe662;</i></a></li>
          <li class="nav-item dropdown">
            <a href="{{ shop_route('account.index') }}" class="nav-link"><i class="iconfont">&#xe619;</i></a>
            <ul class="dropdown-menu dropdown-menu-end">
              @auth('web_shop')
                <li class="dropdown-item">
                  <h6 class="mb-0">{{ current_customer()->name }}</h6>
                </li>
                <li>
                  <hr class="dropdown-divider">
                </li>
                <li><a href="{{ shop_route('account.index') }}" class="dropdown-item"><i class="bi bi-person me-1"></i>
                  {{ __('shop/account.index') }}</a></li>
                <li><a href="{{ shop_route('account.order.index') }}" class="dropdown-item"><i
                      class="bi bi-clipboard-check me-1"></i> {{ __('shop/account.order.index') }}</a></li>
                <li><a href="{{ shop_route('account.wishlist.index') }}" class="dropdown-item"><i
                      class="bi bi-heart me-1"></i> {{ __('shop/account.wishlist.index') }}</a></li>
                <li>
                  <hr class="dropdown-divider">
                </li>
                <li><a href="{{ shop_route('logout') }}" class="dropdown-item"><i class="bi bi-box-arrow-left me-1"></i>
                    {{ __('common.sign_out') }}</a></li>
              @else
                <li><a href="{{ shop_route('login.index') }}" class="dropdown-item"><i
                      class="bi bi-box-arrow-right me-1"></i>{{ __('shop/login.login_and_sign') }}</a></li>
              @endauth
            </ul>
          </li>
          <li class="nav-item">
            <a class="nav-link position-relative" data-bs-toggle="offcanvas" href="#offcanvas-right-cart" role="button"
              aria-controls="offcanvasExample">
              <i class="iconfont">&#xe634;</i>
              {{-- <div class="navbar-icon-link-badge"></div> --}}
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
        <a href="{{ shop_route('account.index') }}" class="nav-link"><i class="iconfont">&#xe619;</i></a>
        <a href="{{ shop_route('carts.index') }}" class="nav-link ms-3"><i class="iconfont">&#xe634;</i></a>
      </div>
    </div>
  </div>
  <div class="offcanvas offcanvas-start" tabindex="-1" id="offcanvas-mobile-menu">
    <div class="offcanvas-header">
      <h5 class="offcanvas-title" id="offcanvasWithBothOptionsLabel">{{ __('common.menu') }}</h5>
      <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body mobile-menu-wrap">
      @if (is_mobile())
        @include('shared.menu-mobile')
      @endif
    </div>
  </div>


  <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvas-right-cart"
    aria-labelledby="offcanvasRightLabel"></div>

  <div class="offcanvas offcanvas-top" tabindex="-1" id="offcanvas-search-top" aria-labelledby="offcanvasTopLabel">
    <div class="offcanvas-header">
      <input type="text" class="form-control input-group-lg border-0 fs-4" focus
        placeholder="{{ __('common.input') }}" aria-label="Type your search here" aria-describedby="button-addon2">
      <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
  </div>
</header>
