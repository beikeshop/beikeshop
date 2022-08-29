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
            {{ current_language() }}
          </a>

          <div class="dropdown-menu" aria-labelledby="language-dropdown">
            @foreach ($languages as $language)
              <a class="dropdown-item"
                href="{{ shop_route('lang.switch', [$language->code]) }}">{{ $language->name }}</a>
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
        <ul class="navbar-nav mx-auto">
          @foreach ($menu_content as $menu)
            <li
              class="nav-item {{ isset($menu['children_group']) ? 'dropdown' : '' }} {{ isset($menu['isFull']) && $menu['isFull'] ? 'position-static' : '' }}">
              <a class="nav-link fw-bold {{ isset($menu['children_group']) ? 'dropdown-toggle' : '' }}"
                target="{{ isset($menu['new_window']) && $menu['new_window'] ? '_blank' : '_self' }}"
                href="{{ $menu['link'] ?? '' }}">
                {{ $menu['name'] }}
                @if (isset($menu['badge']) && $menu['badge']['name'])
                  <span class="badge"
                    style="background-color: {{ $menu['badge']['bg_color'] }}; color: {{ $menu['badge']['text_color'] }}; border-color: {{ $menu['badge']['bg_color'] }}">
                    {{ $menu['badge']['name'] }}
                  </span>
                @endif
              </a>
              @if (isset($menu['children_group']) && $menu['children_group'])
                <div class="dropdown-menu {{ $menu['isFull'] ? 'w-100' : '' }}"
                  style="min-width: {{ count($menu['children_group']) * 200 }}px">
                  <div class="card card-lg">
                    <div class="card-body">
                      <div class="container">
                        <div class="row">
                          @forelse ($menu['children_group'] as $group)
                            <div class="col-6 col-md">
                              @if ($group['name'])
                                <div class="mb-3 fw-bold group-name">{{ $group['name'] }}</div>
                              @endif
                              @if ($group['type'] == 'image')
                                <a
                                target="{{ isset($group['image']['link']['new_window']) && $group['image']['link']['new_window'] ? '_blank' : '_self' }}"
                                href="{{ $group['image']['link'] }}"><img src="{{ $group['image']['image'] }}"
                                    class="img-fluid"></a>
                              @else
                                <ul class="nav flex-column ul-children">
                                  @foreach ($group['children'] as $children)
                                    @if (!is_array($children['link']['text']))
                                      <li class="nav-item">
                                        <a
                                        target="{{ isset($children['link']['new_window']) && $children['link']['new_window'] ? '_blank' : '_self' }}"
                                        class="nav-link px-0"
                                          href="{{ $children['link']['link'] }}">{{ $children['link']['text'] }}</a>
                                      </li>
                                    @endif
                                  @endforeach
                                </ul>
                              @endif
                            </div>
                          @endforeach
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              @endif
            </li>
          @endforeach
        </ul>
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
      <div class="left"><i class="bi bi-list"></i></div>
      <div class="center"><a href="{{ shop_route('home.index') }}">
          <img src="{{ image_origin(system_setting('base.logo')) }}" class="img-fluid"></a>
      </div>
      <div class="right">
        <a href="{{ shop_route('account.index') }}" class="nav-link"><i class="iconfont">&#xe619;</i></a>
        <a href="{{ shop_route('carts.index') }}" class="nav-link ms-3"><i class="iconfont">&#xe634;</i></a>
      </div>
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
