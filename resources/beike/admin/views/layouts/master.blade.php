<!DOCTYPE html>
<html lang="{{ locale() }}">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1">
  <base href="{{ $admin_base_url }}">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <meta name="asset" content="{{ asset('/') }}">
  <meta name="editor_language" content="{{ locale() }}">
  <script src="{{ asset('vendor/vue/2.7/vue.js') }}"></script>
  <script src="{{ asset('vendor/element-ui/2.15.6/js.js') }}"></script>
  <script src="{{ asset('vendor/jquery/jquery-3.6.0.min.js') }}"></script>
  <script src="{{ asset('vendor/layer/3.5.1/layer.js') }}"></script>
  <script src="{{ asset('vendor/bootstrap/5.1.3/js/bootstrap.bundle.min.js') }}"></script>
  <link href="{{ mix('/build/beike/admin/css/bootstrap.css') }}" rel="stylesheet">
  <link rel="stylesheet" href="{{ asset('vendor/element-ui/2.15.6/css.css') }}">
  @if (locale() != 'zh_cn')
    <script src="{{ asset('vendor/element-ui/language/' . locale() . '.js') }}"></script>
  @endif
  <link href="{{ mix('build/beike/admin/css/app.css') }}" rel="stylesheet">
  <script src="{{ mix('build/beike/admin/js/app.js') }}"></script>
  <title>BeikeShop - @yield('title')</title>
  @stack('header')
  {{-- <x-analytics /> --}}
</head>

<body class="@yield('body-class')">
  <x-admin-header />

  <div class="main-content">
    <aside class="sidebar navbar-expand-xs border-radius-xl">
      <x-admin-sidebar />
    </aside>
    <div id="content">
      <div class="page-title-box py-1 d-flex align-items-center justify-content-between">
        <h5 class="page-title">@yield('title')</h5>
        @yield('page-title-right')
      </div>
      <div class="container-fluid p-0">
        @yield('content')

        <p class="text-center text-secondary mt-5">
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
    }
  </script>
  @stack('footer')
</body>

</html>
