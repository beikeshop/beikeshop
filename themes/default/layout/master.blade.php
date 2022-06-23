<!doctype html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>首页</title>
  <link rel="stylesheet" type="text/css" href="{{ asset('/build/beike/shop/default/css/bootstrap.css') }}">
  <script src="{{ asset('vendor/jquery/jquery-3.6.0.min.js') }}"></script>
  <script src="{{ asset('vendor/bootstrap-4.6.1/js/bootstrap.bundle.min.js') }}"></script>
  <script src="{{ asset('vendor/bootstrap-4.6.1/js/bootstrap.min.js') }}"></script>
  <link rel="stylesheet" type="text/css" href="{{ asset('/build/beike/shop/default/css/app.css') }}">
  @stack('header')
</head>
<body>
  @include('layout.header')

  @yield('content')

  @include('layout.footer')
</body>
</html>
