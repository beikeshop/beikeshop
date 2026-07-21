<!DOCTYPE html>
<html lang="en" data-bs-theme="{{ system_setting('base.bd_theme', 'light') }}">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1">
  <link href="{{ mix('/build/beike/admin/css/bootstrap.css') }}" rel="stylesheet">
  <link href="{{ mix('build/beike/admin/css/app.css') }}" rel="stylesheet">
  <title>{{ __('admin/login.plugins_index', ['store_name' => system_setting('base.store_name', 'BeikeShop')]) }}</title>

  <script>
    @if (system_setting('base.bd_theme') == '')
      if (window.matchMedia('(prefers-color-scheme: dark)').matches) {
        document.documentElement.setAttribute('data-bs-theme', 'dark');
      } else {
        document.documentElement.setAttribute('data-bs-theme', 'light');
      }
    @endif
  </script>
</head>
<body class="page-login">
  <div class="d-flex align-items-center vh-100 pt-2 pt-sm-5 pb-4 pb-sm-5">
    <div class="container">
      <div class="card">
        <div class="w-480">
          <div class="card-header mt-3 mb-4">
            <h4 class="fw-bold">{{ __('admin/login.plugins_index', ['store_name' => system_setting('base.store_name', 'BeikeShop')]) }}</h4>
            {{-- <div class="text-muted fw-normal">{{ __('admin/login.plugins_index') }}</div> --}}
          </div>

          <div class="card-body">
            <form action="{{ admin_route('login.store') }}" method="post">
              @csrf

              <div class="form-floating mb-4">
                <input type="text" name="email" class="form-control" id="email-input" value="{{ old('email', $admin_email ?? '') }}" placeholder="{{ __('common.email') }}">
                <label for="email-input">{{ __('common.email') }}</label>
                @error('email')
                  <x-admin::form.error :message="$message" />
                @enderror
              </div>

              <div class="form-floating mb-5">
                <input type="password" name="password" class="form-control" id="password-input" value="{{ old('password', $admin_password ?? '') }}" placeholder="{{ __('shop/login.password') }}">
                <label for="password-input">{{ __('shop/login.password') }}</label>
                @error('password')
                  <x-admin::form.error :message="$message" />
                @enderror
              </div>

              @if (session('error'))
                <div class="alert alert-danger">
                  {{ session('error') }}
                </div>
              @endif

              <div class="d-grid mb-4"><button type="submit" class="btn btn-lg btn-primary">{{ __('admin/login.log_in') }}</button></div>
              <a href="{{ admin_route('forgotten.index') }}" class="text-muted"><i class="bi bi-question-circle"></i> {{ __('admin/login.forgot_password') }}</a>
            </form>
          </div>
        </div>
      </div>

      <div class="text-center text-secondary mt-5">
        @if(!check_license())
        <p class="mb-0"> <a href="https://beikeshop.com/" class="ms-2" target="_blank">BeikeShop</a> v{{ config('beike.version') }}({{ config('beike.build') }}) &copy; {{ date('Y') }} All Rights Reserved</p>
        @else
        @php
          $footerContent = \Beike\Repositories\FooterRepo::handleFooterData();
          $copyright = $footerContent['bottom']['copyright'][locale()] ?? '';
        @endphp
        <div class="copyright-content d-flex justify-content-center" style="text-align: center;">
          <span class="me-2">v{{ config('beike.version') }}({{ config('beike.build') }})</span>
          <div>{!! $copyright !!}</div>
        </div>
        @endif
      </div>
    </div>
  </div>
</body>
</html>
