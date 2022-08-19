<!DOCTYPE html>
<html>

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>
    @if (trim($__env->yieldContent('template_title')))
      @yield('template_title') |
    @endif {{ trans('installer::installer_messages.title') }}
  </title>
  <link rel="stylesheet" type="text/css" href="{{ asset('/build/beike/shop/default/css/bootstrap.css') }}">
  <script src="{{ asset('vendor/jquery/jquery-3.6.0.min.js') }}"></script>
  <script src="{{ asset('vendor/layer/3.5.1/layer.js') }}"></script>
  <link rel="shortcut icon" href="{{ asset('/image/favicon.png') }}">
  <script src="{{ asset('vendor/bootstrap/5.1.3/js/bootstrap.min.js') }}"></script>
  <link rel="stylesheet" type="text/css" href="{{ asset('/build/beike/installer/app.css') }}">
  @yield('style')
</head>

<body>
  <aside class="aside-wrap">
    <div class="logo mb-5"><img src="{{ asset('/image/logo.png') }}" class="img-fluid"></div>
    <div class="steps-wrap">
      <ul>
        <li class="success">
          <div class="left"><span class="index"><i class="bi bi-check-lg"></i></span>已经成功的步骤</div>
        </li>
        <li class="ing">
          <div class="left">
            <span class="index">2</span>正在进行的步骤
          </div>
          <span class="right"><i class="bi bi-arrow-right-short"></i></span>
        </li>
        <li><div class="left"><span class="index">3</span>等待执行的步骤</div></li>
        <li><div class="left"><span class="index">4</span>等待执行的步骤</div></li>
        <li><div class="left"><span class="index">5</span>等待执行的步骤</div></li>
      </ul>
    </div>
    {{-- <div class="container d-flex justify-content-between align-items-center">
    </div> --}}
  </aside>

  <div class="content">
    @if (session('message'))
      <p class="alert text-center">
        <strong>
          @if (is_array(session('message')))
            {{ session('message')['message'] }}
          @else
            {{ session('message') }}
          @endif
        </strong>
      </p>
    @endif
    @if (session()->has('errors'))
      <div class="alert alert-danger" id="error_alert">
        <ul>
          @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
          @endforeach
        </ul>
      </div>
    @endif

    @yield('content')
  </div>
  @yield('scripts')
</body>

</html>
