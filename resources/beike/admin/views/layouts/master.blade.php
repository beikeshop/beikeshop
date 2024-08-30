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
  <title>BeikeShop - @yield('title')</title>
  @stack('header')
  {{-- <x-analytics /> --}}
  <script>
    const $languages = @json(locales());
    const $locale = '{{ locale() }}';
  </script>
</head>

<body class="@yield('body-class') {{ admin_locale() }}">
  <x-admin-header />

  <div class="main-content">
    <x-admin-sidebar />
    <div id="content">
      <div class="page-title-box py-1 d-flex align-items-center justify-content-between">
        <div class="d-flex align-items-center">
          <h5 class="page-title">@yield('title')</h5>
          <div class="ms-4 text-danger">@yield('page-title-after')</div>
        </div>
        <div class="text-nowrap">@yield('page-title-right')</div>
      </div>
      <div class="container-fluid p-0">
        <div class="content-info">@yield('content')</div>

        <div class="page-bottom-btns">
          @yield('page-bottom-btns')
        </div>

        <p class="text-center text-secondary mt-5" id="copyright-text">
            <a href="https://beikeshop.com/" class="ms-2" target="_blank">BeikeShop</a>
            v{{ config('beike.version') }}({{ config('beike.build') }})
            &copy; {{ date('Y') }} All Rights Reserved</p>

      </div>
    </div>
  </div>

  <script>
    @if (locale() != 'zh_cn')
      ELEMENT.locale(ELEMENT.lang['{{ locale() }}'])
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
      app_url: '{{ request()->getHost() }}',
      has_license: {{ json_encode(check_license()) }},
      has_license_code: '{{ system_setting("base.license_code", "") }}',
    }

    function languagesFill(text) {
      var obj = {};
      $languages.map(e => {
        obj[e.code] = text
      })

      return obj;
    }

    @if (!check_same_domain())
      layer.alert('{{ __('admin/common.error_host_app_url') }}', {
        icon: 0,
        title: '{{__("common.text_hint")}}',
        area: ['400px', '200px'],
        btn: ['{{ __('common.confirm') }}']
      })
    @endif
  </script>
  @stack('footer')
</body>
</html>
