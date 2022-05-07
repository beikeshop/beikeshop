<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1">
  <script src="//cdn.bootcdn.net/ajax/libs/vue/2.6.14/vue.js"></script>
{{--   <script src="{{ asset('vendor/vue/2.6.12/vue' . (config('app.debug') ? '' : '.min') . '.js') }}"></script>
  <script src="{{ asset('vendor/element-ui/2.15.6/js.js') }}"></script>
  <script src="{{ asset('vendor/jquery/3.6.0/jquery.min.js') }}"></script>
  <script src="{{ asset('vendor/axios/0.21.1/axios.min.js') }}"></script>
  <script src="{{ asset('vendor/layer/3.5.1/layer.js') }}"></script>
  <script src="{{ mix('build/js/app.js') }}"></script> --}}
  <script src="https://unpkg.com/element-ui/lib/index.js"></script>

  <link href="{{ mix('build/css/bootstrap.css') }}" rel="stylesheet">
  <link rel="stylesheet" href="https://unpkg.com/element-ui/lib/theme-chalk/index.css">
  @if (0)
  <link rel="stylesheet" href="{{ asset('vendor/element-ui/2.15.6/css.css') }}">
  @endif
  <link href="{{ mix('build/css/admin/app.css') }}" rel="stylesheet">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>beike admin</title>
  @stack('header')
  {{-- <x-analytics /> --}}
</head>
<body class="@yield('body-class')">
  <!-- <div style="height: 80px; background: white;"></div> -->

  <x-admin.header />

  <div class="main-content">
    <aside class="sidebar navbar-expand-xs border-radius-xl">
      <x-admin.sidebar />
    </aside>
    <div id="content">
      <div class="container-fluid p-0">
        <div class="page-title-box"><h4 class="page-title">@yield('title')</h4></div>
        @yield('content')
      </div>
    </div>
  </div>
  @stack('footer')
</body>
</html>
