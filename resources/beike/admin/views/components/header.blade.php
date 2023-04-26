<div class="header-content d-none d-lg-block">
  <div class="header-wrap">
    <div class="header-left">
      <div class="logo">
        <a href=""><img src="{{ asset('image/logo.png') }}" class="img-fluid"></a>
      </div>
    </div>
    <div class="header-right">
      {{-- <ul class="navbar navbar-left">
        @foreach ($links as $link)
          <li class="nav-item {{ $link['active'] ? 'active' : '' }}"><a href="{{ $link['url'] }}" class="nav-link">{{ $link['title'] }}</a></li>
        @endforeach
      </ul> --}}
      <div class="search-wrap">
        <div class="input-wrap">
          <div class="search-icon"><i class="bi bi-search"></i></div>
          <input type="text" class="form-control" placeholder="Search in front">
          <button class="btn close-icon" type="button"><i class="bi bi-x-lg"></i></button>
        </div>

        <div class="dropdown-menu">
          <div class="dropdown-wrap">
            <div class="link-item recent-search">
              <div class="dropdown-header fw-bold mb-2">最近搜索</div>
              <div class="recent-search-links">
                <a href="{{ admin_route('design_menu.index') }}"><i class="bi bi-search"></i> {{ __('admin/common.design_menu_index') }}</a>
                <a href="{{ admin_route('languages.index') }}"><i class="bi bi-search"></i> {{ __('admin/common.languages_index') }}</a>
                <a href="{{ admin_route('currencies.index') }}"><i class="bi bi-search"></i> {{ __('admin/common.currencies_index') }}</a>
                <a href="{{ admin_route('plugins.index') }}"><i class="bi bi-search"></i> {{ __('admin/common.plugins_index') }}</a>
              </div>
            </div>
            <div class="dropdown-divider"></div>
            <div class="link-item">
              <div class="dropdown-header fw-bold">常用链接</div>
              <div class="common-links">
                <a class="dropdown-item" href="{{ admin_route('design.index') }}" target="_blank">
                  <span><i class="bi bi-palette"></i></span> {{ __('admin/common.design_index') }}
                </a>
                <a class="dropdown-item" href="{{ admin_route('design_footer.index') }}" target="_blank">
                  <span><i class="bi bi-palette"></i></span> {{ __('admin/common.design_footer_index') }}
                </a>
                <a class="dropdown-item" href="{{ admin_route('design_menu.index') }}">
                  <span><i class="bi bi-list"></i></span> {{ __('admin/common.design_menu_index') }}
                </a>
                <a class="dropdown-item" href="{{ admin_route('languages.index') }}">
                  <span><i class="bi bi-globe2"></i></span> {{ __('admin/common.languages_index') }}
                </a>
                <a class="dropdown-item" href="{{ admin_route('currencies.index') }}">
                  <span><i class="bi bi-currency-dollar"></i></span> {{ __('admin/common.currencies_index') }}
                </a>
                <a class="dropdown-item" href="{{ admin_route('plugins.index') }}">
                  <span><i class="bi bi-plug"></i></span> {{ __('admin/common.plugins_index') }}
                </a>
              </div>
            </div>
          </div>
        </div>
      </div>
      <ul class="navbar navbar-right">
        @hookwrapper('admin.header.upgrade')
        <li class="nav-item update-btn me-2" style="display: none">
          <a href="javascript:void(0)" class="btn btn-outline-primary btn-sm">@lang('admin/common.update_nav')</a>
        </li>
        @endhookwrapper

        @hookwrapper('admin.header.vip')
        <li class="nav-item vip-serve">
          <a href="{{ config('beike.api_url') }}/vip/subscription?domain={{ config('app.url') }}&developer_token={{ system_setting('base.developer_token') }}&type=tab-vip" target="_blank" class="nav-link">
            <img src="/image/vip-icon.png" class="img-fluid">
            <span class="vip-text ms-1">VIP</span>
            <div class="expired-text text-danger ms-2" style="display: none">@lang('admin/common.expired_at')：<span class="ms-0"></span></div>
          </a>
        </li>
        @endhookwrapper

        @hookwrapper('admin.header.license')
        <li class="nav-item">
          <a href="{{ config('beike.api_url') }}/vip/subscription?domain={{ config('app.url') }}&developer_token={{ system_setting('base.developer_token') }}&type=tab-license" target="_blank" class="nav-link">
            <span class="vip-text ms-1">@lang('admin/common.copyright_buy')</span>
          </a>
        </li>
        @endhookwrapper

        @hookwrapper('admin.header.marketing')
        <li class="nav-item">
          <a href="{{ admin_route('marketing.index') }}" class="nav-link">@lang('admin/common.marketing')</a>
        </li>
        @endhookwrapper

        @hookwrapper('admin.header.language')
        <li class="nav-item">
          <div class="dropdown">
            <a class="nav-link dropdown-toggle" href="javascript:void(0)" data-bs-toggle="dropdown">{{ $admin_language['name'] }}</a>

            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownMenuLink">
              @foreach ($admin_languages as $language)
              <li><a href="{{ admin_route('edit.locale', ['locale' => $language['code']]) }}" class="dropdown-item">{{ $language['name'] }}</a></li>
              @endforeach
            </ul>
          </div>
        </li>
        @endhookwrapper

        @hookwrapper('admin.header.user')
        <li class="nav-item me-3">
          <div class="dropdown">
            <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">
              <span class="text-dark ml-2">{{ current_user()->name }}</span>
            </a>

            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownMenuLink">
              <li>
                <a target="_blank" href="{{ shop_route('home.index') }}" class="dropdown-item"><i class="bi bi-send me-1"></i> @lang('admin/common.access_frontend')</a>
              </li>
              <li><a href="{{ admin_route('account.index') }}" class="dropdown-item"><i class="bi bi-person-circle"></i> {{ __('admin/common.account_index') }}</a></li>
              <li><hr class="dropdown-divider"></li>
              <li><a href="{{ admin_route('logout.index') }}" class="dropdown-item"><i class="bi bi-box-arrow-left me-1"></i> {{ __('common.sign_out') }}</a></li>
            </ul>
          </div>
        </li>
        @endhookwrapper
      </ul>
    </div>
  </div>
</div>

<div class="header-mobile d-lg-none">
  <div class="header-mobile-wrap">
    <div class="header-mobile-left">
      <div class="mobile-open-menu"><i class="bi bi-list"></i></div>
    </div>
    <div class="logo">
      <a href=""><img src="{{ asset('image/logo.png') }}" class="img-fluid"></a>
    </div>
    <div class="header-mobile-right">
      <div class="mobile-to-front">
        <a target="_blank" href="{{ shop_route('home.index') }}" class="nav-divnk"><i class="bi bi-send"></i></a>
      </div>
    </div>
  </div>
</div>

<div class="offcanvas offcanvas-start" tabindex="-1" id="offcanvas-mobile-menu">
  <div class="offcanvas-header">
    <h5 class="offcanvas-title fw-bold" id="offcanvasWithBothOptionsLabel">{{ __('common.menu') }}</h5>
    <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
  </div>
  <div class="offcanvas-body mobile-menu-wrap">
    @if (is_mobile())
      <ul class="mobile-navbar">
        @foreach ($links as $link)
          <li class="nav-item {{ $link['active'] ? 'active' : '' }}">
            <a href="{{ $link['url'] }}" class="nav-link">{{ $link['title'] }}</a>
            @if ($link['active'])
            <x-admin-sidebar />
            @endif
          </li>
        @endforeach
      </ul>
    @endif
  </div>

  <div class="offcanvas-footer">
    <div class="offcanvas-btns">
      <div class="lang">
        <div class="dropdown">
          <a class="nav-link dropdown-toggle text-dark" href="javascript:void(0)" data-bs-toggle="dropdown">{{ $admin_language['name'] }}</a>

          <ul class="dropdown-menu" aria-labelledby="dropdownMenuLink">
            @foreach ($admin_languages as $language)
            <li><a href="{{ admin_route('edit.locale', ['locale' => $language['code']]) }}" class="dropdown-item">{{ $language['name'] }}</a></li>
            @endforeach
          </ul>
        </div>
      </div>
      <div class="user">
        <a href="{{ admin_route('logout.index') }}" class="nav-link text-dark"><i class="bi bi-box-arrow-left"></i> {{ __('common.sign_out') }}</a>
      </div>
    </div>
  </div>
</div>
<div class="update-pop p-3" style="display: none">
  <div class="mb-4 fs-5 fw-bold text-center">{{ __('admin/common.update_title') }}</div>
  <div class="py-3 px-4 bg-light mx-3 lh-lg mb-4">
    <div>{{ __('admin/common.update_new_version') }}：<span class="new-version fs-5 fw-bold text-success"></span></div>
    <div>{{ __('admin/common.update_old_version') }}：<span class="fs-5">{{ config('beike.version') }}</span></div>
    <div>{{ __('admin/common.update_date') }}：<span class="update-date fs-5"></span></div>
  </div>

  <div class="d-flex justify-content-center mb-3">
    <button class="btn btn-outline-secondary me-3 ">{{ __('common.cancel') }}</button>
    <a href="https://beikeshop.com/download" target="_blank" class="btn btn-primary">{{ __('admin/common.update_btn') }}</a>
  </div>
</div>


@push('footer')
  <script>
    let updatePop = null;

    $('.update-btn').click(function() {
      updatePop = layer.open({
        type: 1,
        title: '{{ __('common.text_hint') }}',
        area: ['400px'],
        content: $('.update-pop'),
      });
    });

    $('.update-pop .btn-outline-secondary').click(function() {
      layer.close(updatePop)
    });
  </script>
@endpush
