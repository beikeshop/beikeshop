<!doctype html>
<html lang="{{ locale() }}">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>@yield('title', system_setting('base.meta_title', 'BeikeShop开源好用的跨境电商系统 - BeikeShop官网'))</title>
  <meta name="keywords" content="@yield('keywords', system_setting('base.meta_keyword'))">
  <meta name="description" content="@yield('description', system_setting('base.meta_description'))">
  <base href="{{ $shop_base_url }}">
  <link rel="stylesheet" type="text/css" href="{{ asset('/build/beike/shop/default/css/bootstrap.css') }}">
  <script src="{{ asset('vendor/jquery/jquery-3.6.0.min.js') }}"></script>
  <script src="{{ asset('vendor/layer/3.5.1/layer.js') }}"></script>
  <link rel="shortcut icon" href="{{ image_origin(system_setting('base.favicon')) }}">
  <script src="{{ asset('vendor/bootstrap/5.1.3/js/bootstrap.min.js') }}"></script>
  <script src="{{ asset('/build/beike/shop/default/js/app.js') }}"></script>
  <link rel="stylesheet" type="text/css" href="{{ asset('/build/beike/shop/default/css/app.css') }}">
  @stack('header')
</head>
<body class="@yield('body-class')">
  @if (!request('iframe'))
    @include('layout.header')
  @endif

  @yield('content')

  @if (!request('iframe'))
    @include('layout.footer')
  @endif

  <script>
    const isLogin = @json(current_customer());
  </script>

  @stack('add-scripts')
</body>
<!-- BeikeShop v{{ config('beike.version') }}({{ config('beike.build') }}) -->
</html>
