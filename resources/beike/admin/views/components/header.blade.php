<div class="header-content d-none d-lg-block">
  <div class="header-wrap">
    <div class="header-left">
      <div class="logo">
        <a href=""><img src="{{ asset('image/logo.png') }}" class="img-fluid"></a>
      </div>
    </div>
    <div class="header-right">
      <ul class="navbar navbar-left">
        @foreach ($links as $link)
          <li class="nav-item {{ $link['active'] ? 'active' : '' }}"><a href="{{ $link['url'] }}" class="nav-link">{{ $link['title'] }}</a></li>
        @endforeach
      </ul>
      <ul class="navbar navbar-right">
        <li class="nav-item update-btn me-2" style="display: none">
          <a href="javascript:void(0)" class="btn btn-outline-primary btn-sm">@lang('admin/common.update_nav')</a>
        </li>
        <li class="nav-item vip-serve">
          <a href="javascript:void(0)" class="nav-link"><img src="/image/vip-icon.png" class="img-fluid"> <span>VIP</span></a>
        </li>
        <li class="nav-item">
          <a href="{{ admin_route('marketing.index') }}" class="nav-link">@lang('admin/common.marketing')</a>
        </li>
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
        <li class="nav-item me-3">
          <div class="dropdown">
            <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">
              <span class="text-dark ml-2">{{ current_user()->name }}</span>
            </a>

            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownMenuLink">
              <li>
                <a target="_blank" href="{{ shop_route('home.index') }}" class="dropdown-item"><i class="bi bi-send me-1"></i> @lang('admin/common.access_frontend')</a>
              </li>
              <li><hr class="dropdown-divider"></li>
              <li><a href="{{ admin_route('logout.index') }}" class="dropdown-item"><i class="bi bi-box-arrow-left me-1"></i> {{ __('common.sign_out') }}</a></li>
            </ul>
          </div>
        </li>
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

    $('.vip-serve').click(function(event) {
      layer.open({
        type: 1,
        title: '',
        area: ['500px', '80%'],
        content: '<div><img src="image/vip-info.webp" class="img-fluid"></div>',
      });
    });
  </script>
@endpush