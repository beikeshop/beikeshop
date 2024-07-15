<!doctype html>
<html lang="{{ locale() }}">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>@yield('title', system_setting('base.meta_title', 'BeikeShop开源好用的跨境电商系统'))</title>
  <meta name="keywords" content="@yield('keywords', system_setting('base.meta_keywords'))">
  <meta name="description" content="@yield('description', system_setting('base.meta_description'))">
  <meta name="generator" content="BeikeShop v{{ config('beike.version') }}({{ config('beike.build') }})">
  <base href="{{ $shop_base_url }}">
  <link rel="stylesheet" type="text/css" href="{{ mix('/build/beike/shop/'.system_setting('base.theme').'/css/bootstrap.css') }}">
  <script src="{{ asset('vendor/jquery/jquery-3.6.0.min.js') }}"></script>
  <script src="{{ asset('vendor/layer/3.5.1/layer.js') }}"></script>
  <script src="{{ asset('vendor/lazysizes/lazysizes.min.js') }}"></script>
  <link rel="shortcut icon" href="{{ image_origin(system_setting('base.favicon')) }}">
  <script src="{{ asset('vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
  <script src="{{ mix('/build/beike/shop/'.system_setting('base.theme').'/js/app.js') }}"></script>
  <link rel="stylesheet" type="text/css" href="{{ mix('/build/beike/shop/'.system_setting('base.theme').'/css/app.css') }}">
  @if (system_setting('base.head_code'))
    {!! system_setting('base.head_code') !!}
  @endif
  @hook('layout.header.code')
  @stack('header')
</head>
<body class="@yield('body-class') {{ request('_from') }}">
  @if (!request('iframe') && request('_from') != 'app')
    @include('layout.header')
  @endif

  @yield('content')

  @if (!request('iframe') && request('_from') != 'app')
    @include('layout.footer')
  @endif

  <script>
    const config = {
      isLogin: !!{{ current_customer()->id ?? 'null' }},
      guestCheckout: !!{{ system_setting('base.guest_checkout', 1) }},
      loginShowPrice: !!{{ system_setting('base.show_price_after_login', 0) }},
    }

    // 如果页面使用了ElementUI，且当前语言不是中文，则加载对应的语言包
    @if (locale() != 'zh_cn')
    if (typeof ELEMENT !== 'undefined') {
        const elLocale = '{{ asset('vendor/element-ui/language/'.locale().'.js') }}';
        document.write("<script src='" + elLocale + "'><\/script>")

        $(function () {
          setTimeout(() => {
            ELEMENT.locale(ELEMENT.lang['{{ locale() }}'])
          }, 0);
        })
      }
    @endif
  </script>

  @stack('add-scripts')
</body>
<!-- BeikeShop v{{ config('beike.version') }}({{ config('beike.build') }}) -->
</html>
