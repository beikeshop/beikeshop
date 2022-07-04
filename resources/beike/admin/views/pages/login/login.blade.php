<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1">
  <link href="{{ mix('/build/beike/admin/css/bootstrap.css') }}" rel="stylesheet">
  {{-- <link href="{{ mix('build/css/admin/login.css') }}" rel="stylesheet"> --}}
  <link href="{{ mix('build/beike/admin/css/app.css') }}" rel="stylesheet">
  <title>admin login</title>
</head>
<body class="page-login">
  <div class="d-flex align-items-center vh-100 pt-2 pt-sm-5 pb-4 pb-sm-5">
    <div class="container">
      <div class="card">
        <div class="w-480">
          <div class="card-header mt-3 mb-4">
            <h4 class="fw-bold">登录到 beikeshop 后台</h4>
            <div class="text-muted fw-normal">登录到 beikeshop 后台</div>
          </div>

          <div class="card-body">
            <form action="{{ admin_route('login.store') }}" method="post">
              @csrf

              <div class="form-floating mb-4">
                <input type="text" name="email" class="form-control" id="email-input" value="{{ old('email') }}" placeholder="邮箱地址">
                <label for="email-input">邮箱地址</label>
                @error('email')
                  <x-admin::form.error :message="$message" />
                @enderror
              </div>

              <div class="form-floating mb-5">
                <input type="password" name="password" class="form-control" id="password-input" placeholder="密码">
                <label for="password-input">密码</label>
                @error('password')
                  <x-admin::form.error :message="$message" />
                @enderror
              </div>

              @if (session('error'))
                <div class="alert alert-success">
                  {{ session('error') }}
                </div>
              @endif

              <div class="d-grid mb-4"><button type="submit" class="btn btn-lg btn-primary">登录</button></div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</body>
</html>
