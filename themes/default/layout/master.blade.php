<!doctype html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>@yield('title', 'BeikeShop开源好用的跨境电商系统 - BeikeShop官网')</title>
  <meta name="keywords" content="@yield('keywords', '开源电商,开源代码,开源电商项目,b2b独立站,dtc独立站,跨境电商网')">
  <meta name="description" content="@yield('description', 'BeikeShop 是一款开源好用的跨境电商建站系统，基于 Laravel 开发。主要面向外贸，和跨境行业。系统提供商品管理、订单管理、会员管理、支付、物流、系统管理等丰富功能')">
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
  @include('layout.header')

  @yield('content')

  @include('layout.footer')

  @stack('add-scripts')
</body>
</html>
