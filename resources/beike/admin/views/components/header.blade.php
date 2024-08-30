<div class="header-content d-none d-lg-block">
  <div class="header-wrap">
    <div class="header-left">
      <div class="logo">
        <a href=""><img src="{{ asset(system_setting('base.admin_logo', 'image/logo.png')) }}" class="img-fluid"></a>
      </div>
    </div>
    <div class="header-right">
      <div class="search-wrap">
        <div class="input-wrap">
          <div class="search-icon"><i class="bi bi-search"></i></div>
          <input type="text" id="header-search-input" autocomplete="off" class="form-control" placeholder="{{ __('admin/common.header_search_input') }}">
          <button class="btn close-icon" type="button"><i class="bi bi-x-lg"></i></button>
        </div>

        <div class="dropdown-menu">
          <div class="search-ing"><i class="el-icon-loading"></i></div>
          <div class="dropdown-search">
            <div class="dropdown-header fw-bold">{{ __('admin/common.header_search_title') }}</div>
            <div class="common-links"></div>
            <div class="header-search-no-data"><i class="bi bi-file-earmark"></i> {{ __('common.no_data') }}</div>
          </div>
          <div class="dropdown-wrap">
            @if ($historyLinks)
              <div class="link-item recent-search">
                <div class="dropdown-header fw-bold mb-2">{{ __('admin/common.recent_view') }}</div>
                <div class="recent-search-links">
                  @foreach ($historyLinks as $link)
                    <a href="{{ $link['url'] }}"><i class="bi bi-search"></i> {{ $link['title'] }}</a>
                  @endforeach
                </div>
              </div>
            @endif
            <div class="link-item">
              <div class="dropdown-header fw-bold">{{ __('admin/common.common_link') }}</div>
              <div class="common-links">
                @foreach ($commonLinks as $link)
                  <a class="dropdown-item" href="{{ $link['url'] }}" target="{{ $link['blank'] ? '_blank' : '_self' }}">
                    <span><i class="{{ $link['icon'] }}"></i></span> {{ $link['title'] }}
                  </a>
                @endforeach
              </div>
            </div>
          </div>
        </div>
      </div>
      <ul class="navbar navbar-right">
        <div class="alert alert-warning mb-0 warning-copyright {{ check_license() ? 'd-none' : '' }}">
          <i class="bi bi-exclamation-triangle-fill"></i> {!! __('admin/common.copyright_hint_text') !!}
        </div>

        <li class="nav-item mx-2 license-ok {{ !check_license() || Str::endsWith(config('app.url'), '.test') ? 'd-none' : '' }}">
          <div class="license-text">
            <img src="{{ asset('image/vip-icon.png') }}" class="img-fluid wh-30 me-1">
            <span>{{ __('admin/common.license_bought') }}</span>
          </div>
        </li>

        @hookwrapper('admin.header.upgrade')
        <li class="nav-item update-btn me-2" style="display: none">
          <a href="javascript:void(0)" class="btn btn-outline-primary btn-sm">@lang('admin/common.update_nav')</a>
        </li>
        @endhookwrapper

        @hookwrapper('admin.header.license')
        <li class="nav-item">
          <a href="{{ beike_api_url() }}/vip/subscription?domain={{ config('app.url') }}&developer_token={{ system_setting('base.developer_token') }}&type=tab-license" target="_blank" class="nav-link">
            <i class="bi bi-wrench-adjustable-circle fs-5 text-info"></i>&nbsp;@lang('admin/common.license_services')
          </a>
        </li>
        @endhookwrapper

        @hookwrapper('admin.header.marketing')
        <li class="nav-item">
          <a href="{{ admin_route('marketing.index') }}" class="nav-link"><i class="bi bi-puzzle fs-5 text-info"></i>&nbsp;@lang('admin/common.marketing')</a>
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
              <span class="ml-2">{{ current_user()->name }}</span>
            </a>

            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownMenuLink">
              <li>
                <a target="_blank" href="{{ shop_route('home.index') }}" class="dropdown-item py-2">
                  <i class="bi bi-send me-1"></i> {{ __('admin/common.access_frontend') }}
                </a>
              </li>
              <li>
                <a href="{{ admin_route('account.index') }}" class="dropdown-item py-2">
                  <i class="bi bi-person-circle me-1"></i> {{ __('admin/common.account_index') }}
                </a>
              </li>
              <li><hr class="dropdown-divider"></li>
              <li>
                <a href="{{ admin_route('logout.index') }}" class="dropdown-item py-2">
                  <i class="bi bi-box-arrow-left me-1"></i> {{ __('common.sign_out') }}
                </a>
              </li>
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
  $(document).on('click', '.get-license-code', function(e) {
    e.preventDefault();
    $http.get(`${config.api_url}/api/licensed_pro`, {domain: config.app_url, from: window.location.pathname}).then((res) => {
      if (res.license_code) {
        $http.put('settings/values', {license_code: res.license_code}, {hload: true});
        $('.license-ok').removeClass('d-none');
        $('.warning-copyright').addClass('d-none');
        $('input[name="license_code"]').val(res.license_code);
      } else {
        layer.alert('{{ __('admin/common.copyright_buy_text') }}', {
          icon: 2,
          title: '{{ __('common.text_hint') }}',
          btn: ['{{ __('common.cancel') }}', '{{ __('common.confirm') }}'],
          btn2: function(index) {
            window.open('https://beikeshop.com/vip/subscription?type=tab-license&domain=' + config.app_url)
            layer.close(index);
          }
        })
      }
    })
  });
</script>