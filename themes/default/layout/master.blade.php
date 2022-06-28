<!doctype html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>首页</title>
  <link rel="stylesheet" type="text/css" href="{{ asset('/build/beike/shop/default/css/bootstrap.css') }}">
  <script src="{{ asset('vendor/jquery/jquery-3.6.0.min.js') }}"></script>
  <script src="{{ asset('vendor/bootstrap/5.1.3/js/bootstrap.bundle.min.js') }}"></script>
  <script src="{{ asset('vendor/bootstrap/5.1.3/js/bootstrap.min.js') }}"></script>
  <script src="{{ asset('/build/beike/shop/default/js/app.js') }}"></script>
  <link rel="stylesheet" type="text/css" href="{{ asset('/build/beike/shop/default/css/app.css') }}">
  @stack('header')
</head>
<body class="@yield('body-class')">
  @include('layout.header')

  @yield('content')

  @include('layout.footer')
</body>
</html>
