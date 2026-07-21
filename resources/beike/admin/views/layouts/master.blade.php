<!DOCTYPE html>
<html lang="{{ admin_locale() }}">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1">
  <base href="{{ $admin_base_url }}">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <meta name="asset" content="{{ asset('/') }}">
  <meta name="editor_language" content="{{ locale() }}">
  <script src="{{ asset('vendor/vue/2.7/vue' . (!config('app.debug') ? '.min' : '') . '.js') }}"></script>
  <script src="{{ asset('vendor/element-ui/index.js') }}"></script>
  <script src="{{ asset('vendor/jquery/jquery-3.6.0.min.js') }}"></script>
  <script src="{{ asset('vendor/layer/3.5.1/layer.js') }}"></script>
  <script src="{{ asset('vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
  <script src="{{ asset('vendor/cookie/js.cookie.min.js') }}"></script>
  <link href="{{ mix('/build/beike/admin/css/bootstrap.css') }}" rel="stylesheet">
  <link rel="stylesheet" href="{{ asset('vendor/element-ui/index.css') }}">
  @if (locale() != 'zh_cn')
    <script src="{{ asset('vendor/element-ui/language/' . locale() . '.js') }}"></script>
  @endif
  <link rel="shortcut icon" href="{{ image_origin(system_setting('base.favicon')) }}">
  <link href="{{ mix('build/beike/admin/css/app.css') }}" rel="stylesheet">
  <script src="{{ mix('build/beike/admin/js/app.js') }}"></script>
  <title>{{ system_setting('base.store_name', 'BeikeShop') }} @hasSection('title') - @yield('title') @endif</title>
  @stack('header')

  <script>
    const $languages = @json(locales());
    const $locale = '{{ locale() }}';

    const savedTheme = localStorage.getItem('ld_theme');
    const ldTheme = savedTheme === 'auto'
      ? (window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light')
      : (savedTheme || 'light');

    $('html').attr('data-bs-theme', ldTheme);
  </script>

  @hook('admin.master.header.code')
</head>

<body class="@yield('body-class') {{ admin_locale() }}">
  <x-admin-header />
  @php
    $backUrl = trim($__env->yieldContent('page-title-back'));
  @endphp

  <div class="main-content">
    <x-admin-sidebar />

    <div id="content">
      <div class="content-area mx-auto @yield('content-area-class')">
        @hook('admin.master.content.before')

        <x-admin::page-title-box :back-url="$backUrl" />

        @hook('admin.master.content.before.container')

        <div class="container-fluid p-0">
          <div class="content-info">
            @yield('content')
          </div>

          <div class="page-bottom-btns">
            @yield('page-bottom-btns')
          </div>

          <div class="d-flex justify-content-center mt-5" id="copyright-text">
            <div class="copyright-content text-secondary">
              @if(!check_license())
              <span class="me-1 fl">v{{ config('beike.version') }}({{ config('beike.build') }})</span>
              <span class="me-1">{!! __('common.text_powered') !!}</span>
              @endif
              <div>
                @php
                  $footerContent = \Beike\Repositories\FooterRepo::handleFooterData();
                  $copyright = $footerContent['bottom']['copyright'][locale()] ?? '';
                  @endphp
                {!! $copyright !!}
                @if(check_license())
                <div class="me-1 text-center">v{{ config('beike.version') }}({{ config('beike.build') }})</div>
                @endif
              </div>
            </div>
          </div>

        </div>

        @hook('admin.master.content.after')
      </div>
    </div>
  </div>

  <script>
    @hook('admin.master.script.before')

    @if (locale() != 'zh_cn')
      ELEMENT.locale(ELEMENT.lang?.['{{ locale() }}'])
    @endif
    const lang = {
      file_manager: '{{ __('admin/file_manager.file_manager') }}',
      error_form: '{{ __('common.error_form') }}',
      text_hint: '{{ __('common.text_hint') }}',
      translate_form: '{{ __('admin/common.translate_form') }}',
      choose: '{{ __('common.choose') }}',
    }

    const config = {
      beike_version: '{{ config('beike.version') }}',
      api_url: '{{ beike_api_url() }}',
      version_check_url: '{{ admin_route('marketing.version_check') }}',
      app_url: '{{ request()->getHost() }}',
      has_license: {{ json_encode(check_license()) }},
      placeholder: '{{ system_setting('base.placeholder') }}',
      has_license_code: '{{ system_setting("base.license_code", "") }}',
    }

    function languagesFill(text) {
      var obj = {};
      $languages.map(e => {
        obj[e.code] = text
      })

      return obj;
    }

    if (window.bk && typeof bk.tableResponsive === 'function') {
      bk.tableResponsive()
    }

    @php($domainCompareInfo = get_domain_compare_info())
    @php(
      $domainMismatchMessage = data_get($domainCompareInfo, 'is_app_url_configured')
        ? __('admin/common.error_host_app_url_detail', [
            'current_domain' => data_get($domainCompareInfo, 'current_domain'),
            'app_url_domain' => data_get($domainCompareInfo, 'app_url_domain') ?: data_get($domainCompareInfo, 'app_url'),
          ])
        : __('admin/common.error_host_app_url_empty', [
            'current_domain' => data_get($domainCompareInfo, 'current_domain'),
          ])
    )
    @if (!data_get($domainCompareInfo, 'same_domain'))
      setTimeout(() => {
        layer.alert(@json($domainMismatchMessage), {
          icon: 0,
          title: '{{__("common.text_hint")}}',
          area: ['480px'],
          btn: ['{{ __('common.confirm') }}']
        })
      }, 1000);
    @endif

    $('.exit-edit').on('click', function() {
      layer.confirm('{{ __('common.exit_edit_tips') }}', {
        icon: 0,
        title: '{{__("common.text_hint")}}',
        btn: ['{{ __('common.cancel') }}', '{{ __('common.confirm') }}']
      }, function() {
        layer.closeAll();
      }, function() {
        @if ($backUrl && $backUrl !== '1')
        window.location.href = "{{ $backUrl }}";
        @else
        history.go(-1);
        @endif
      })
    })

    @hook('admin.master.script.after')
  </script>

  @stack('footer')
  @hook('admin.master.footer')
</body>
</html>
