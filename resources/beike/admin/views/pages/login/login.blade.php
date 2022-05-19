<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Document</title>
</head>
<body>
  <form action="{{ admin_route('login.store') }}" method="post">
    @csrf

    <div class="form-group">
      <div class="input-group">
        <div class="input-group-prepend">
          <span class="input-group-text" id="email">邮箱</span>
        </div>
        <input type="text" name="email" class="form-control" value="{{ old('email') }}" placeholder="邮箱地址">
      </div>
      @error('email')
        <x-admin::form.error :message="$message" />
      @enderror
    </div>

    <div class="form-group">
      <div class="input-group">
        <div class="input-group-prepend">
          <span class="input-group-text" id="password">密码</span>
        </div>
        <input type="password" name="password" class="form-control" placeholder="密码">
      </div>
      @error('password')
        <x-admin::form.error :message="$message" />
      @enderror
    </div>

    @if (session('error'))
      <div class="alert alert-success">
        {{ session('error') }}
      </div>
    @endif

    <button type="submit" class="btn btn-primary btn-block mb-4">登录</button>
  </form>
</body>
</html>
