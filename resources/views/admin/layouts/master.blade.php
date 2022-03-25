<!doctype html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>Document</title>
  <script src="https://cdn.bootcdn.net/ajax/libs/vue/2.6.14/vue.js"></script>
  <link href="https://cdn.bootcdn.net/ajax/libs/twitter-bootstrap/4.6.1/css/bootstrap.min.css" rel="stylesheet">
  <link href="{{ mix('build/css/app.css') }}" rel="stylesheet">
  @stack('header')
</head>
<body>
  <div>
    <div>sidebar</div>
    <div>
      @yield('content')
    </div>
  </div>

</body>

@stack('footer')
</html>
