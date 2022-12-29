<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>@yield('title')</title>
  <link rel="stylesheet" type="text/css" href="{{ asset('/build/beike/shop/default/css/bootstrap.css') }}">
</head>

<body class="page-400">
  <div class="flex-center position-ref full-height">
    <div class="content">
      <div class="title">
        @yield('content')
      </div>
    </div>
  </div>
</body>

</html>
