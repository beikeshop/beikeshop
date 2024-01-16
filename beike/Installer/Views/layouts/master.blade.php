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
  <script src="{{ asset('vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
  <link rel="stylesheet" type="text/css" href="{{ asset('/install/css/app.css') }}">
  @yield('style')
</head>

<body>
  <div class="install-box">
    <aside class="aside-wrap">
      <div class="top">
        <div class="logo mb-5"><img src="{{ asset('/image/logo.png') }}" class="img-fluid"></div>
        <div class="steps-wrap">
          <ul>
            <li class="{{ $steps == 1 ? 'ing' : '' }} {{ $steps > 1 ? 'success' : '' }}">
              <div class="left">
                 <span class="index">@if ($steps > 1) <i class="bi bi-check-lg"></i> @else 1 @endif</span>
                 {{ __('installer::installer_messages.welcome.title') }}
                </div>
              <span class="right"><i class="bi bi-arrow-right-short"></i></span>
            </li>
            <li class="{{ $steps == 2 ? 'ing' : '' }} {{ $steps > 2 ? 'success' : '' }}">
              <div class="left">
                <span class="index">@if ($steps > 2 && $steps != 2) <i class="bi bi-check-lg"></i> @else 2 @endif</span>
                {{ __('installer::installer_messages.requirements.title') }}
              </div>
              <span class="right"><i class="bi bi-arrow-right-short"></i></span>
            </li>
            <li class="{{ $steps == 3 ? 'ing' : '' }} {{ $steps > 3 ? 'success' : '' }}">
              <div class="left">
                <span class="index">@if ($steps > 3 && $steps != 3) <i class="bi bi-check-lg"></i> @else 3 @endif</span>
                {{ __('installer::installer_messages.permissions.title') }}
              </div>
              <span class="right"><i class="bi bi-arrow-right-short"></i></span>
            </li>
            <li class="{{ $steps == 4 ? 'ing' : '' }} {{ $steps > 4 ? 'success' : '' }}">
              <div class="left">
                <span class="index">@if ($steps > 4 && $steps != 4) <i class="bi bi-check-lg"></i> @else 4 @endif</span>
                {{ __('installer::installer_messages.environment.title') }}
              </div>
              <span class="right"><i class="bi bi-arrow-right-short"></i></span>
            </li>
            <li class="{{ $steps == 5 ? 'ing' : '' }} {{ $steps > 5 ? 'success' : '' }}">
              <div class="left">
                <span class="index">@if ($steps > 5 && $steps != 5) <i class="bi bi-check-lg"></i> @else 5 @endif</span>
                {{ __('installer::installer_messages.final.template_title') }}
              </div>
              <span class="right"><i class="bi bi-arrow-right-short"></i></span>
            </li>
          </ul>
        </div>
      </div>
      <div class="bottom">
      </div>
    </aside>

    <div class="content">
      @if (session()->has('errors'))
        <div class="alert alert-danger" id="error_alert">
          @foreach ($errors->all() as $error)
            <div>{{ $error }}</div>
          @endforeach
        </div>
      @endif

      @yield('content')
    </div>
  </div>
  @yield('scripts')

  <script>
    $(document).ready(function() {
      $('html, body').css('height', $(window).height());
    });
  </script>
</body>
</html>
