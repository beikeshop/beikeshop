<!doctype html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport"
    content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>首页编辑器</title>
  <script src="{{ asset('vendor/jquery/jquery-3.6.0.min.js') }}"></script>
  <script src="{{ asset('vendor/layer/3.5.1/layer.js') }}"></script>
  <script src="{{ asset('/build/beike/shop/default/js/app.js') }}"></script>
  <script src="{{ asset('vendor/vue/2.6.14/vue.js') }}"></script>
  <script src="{{ asset('vendor/element-ui/2.15.6/js.js') }}"></script>
  <link rel="stylesheet" href="{{ asset('vendor/element-ui/2.15.6/css.css') }}">
  <link rel="stylesheet" type="text/css" href="{{ asset('/build/beike/shop/default/css/design/app.css') }}">
  @stack('header')
</head>

<body class="page-design">
  <div class="design-box">
    <div class="sidebar-edit-wrap">

    </div>
    <div class="preview-iframe">
      <iframe src="{{ shop_route('home.index') }}" frameborder="0" id="preview-iframe" width="100%" height="100%"></iframe>
    </div>
  </div>
</body>

</html>
